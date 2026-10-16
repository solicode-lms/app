<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Illuminate\Support\Facades\Auth;
use Modules\PkgAutorisation\Models\Role;

trait DashboardConfigTrait
{
    /**
     * Résout le rôle effectif de l'utilisateur pour le tableau de bord.
     *
     * @param mixed $user
     * @return string ('root'|'admin'|'formateur'|'apprenant')
     */
    public function resolveDashboardRole($user = null): string
    {
        $user = $user ?: Auth::user();
        if (!$user) {
            return 'apprenant';
        }

        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('root')) {
                return 'root';
            }
            if ($user->hasAnyRole(['admin', 'responsable', Role::ADMIN_ROLE ?? 'admin'])) {
                return 'admin';
            }
            if ($user->hasRole(Role::FORMATEUR_ROLE ?? 'formateur')) {
                return 'formateur';
            }
            if ($user->hasRole(Role::APPRENANT_ROLE ?? 'apprenant')) {
                return 'apprenant';
            }
        }

        return 'apprenant';
    }

    /**
     * Vérifie si l'accès à la page d'analyse détaillée d'un indicateur est autorisé pour l'utilisateur.
     *
     * @param string $indicateurKey
     * @param mixed $user
     * @return bool
     */
    public function isIndicateurDetailAccessible(string $indicateurKey, $user = null): bool
    {
        $role = $this->resolveDashboardRole($user);
        if ($role === 'root') {
            return true;
        }

        $catalogue = config('dashboard.indicateurs', []);
        if (!isset($catalogue[$indicateurKey])) {
            return false;
        }

        $roleConfig = config("dashboard.roles.{$role}.indicateurs.{$indicateurKey}", []);
        $isEnabled = !empty($roleConfig['enabled']);
        $showDetail = !empty($roleConfig['show_detail']);

        return $isEnabled && $showDetail;
    }

    /**
     * Résout et construit les collections de Cards et de Panels à afficher.
     *
     * @param mixed $user
     * @param array $kpis
     * @param bool $isSessionFormation
     * @param string|null $contextRole (Optionnel pour forcer une vue supervisée, ex: admin consultant une vue formateur)
     * @return array ['cards' => array, 'panels' => array, 'role' => string]
     */
    public function resolveDashboardComponents($user = null, array $kpis = [], bool $isSessionFormation = true, ?string $contextRole = null): array
    {
        $user = $user ?: Auth::user();
        $userRole = $this->resolveDashboardRole($user);
        $role = $contextRole ?: $userRole;

        // Si l'utilisateur est root, il conserve la visibilité totale même en consultation contextuelle
        if ($userRole === 'root' && !$contextRole) {
            $role = 'root';
        }

        $catalogue = config('dashboard.indicateurs', []);
        $rolesConfig = config('dashboard.roles', []);
        $currentRoleConfig = $rolesConfig[$role] ?? ($rolesConfig['apprenant'] ?? []);
        $isDisplayAll = !empty($currentRoleConfig['display_all']) || ($role === 'root');

        $dashboardCards = [];
        $dashboardPanels = [];

        // Table de correspondance entre clé d'indicateur et clés KPI passées par les contrôleurs
        $kpiKeyMap = [
            'amorcage'          => ['kpiAmorcage', 'amorcage'],
            'gating'            => ['kpiGating', 'gating'],
            'revision'          => ['kpiRevision', 'revision'],
            'charge_logistique' => ['kpiChargeLogistique', 'charge_logistique'],
            'file_attente'      => ['kpiFileAttente', 'file_attente'],
            'intervention'      => ['kpiIntervention', 'intervention'],
            'trvs'              => ['kpiIntegriteIA', 'trvs', 'integrite_ia'],
            'couverture'        => ['kpiCouverture', 'couverture', 'couverture_pedagogique'],
            'planification_ua'  => ['kpiDocimologie', 'planification_ua', 'docimologie'],
        ];

        foreach ($catalogue as $indKey => $meta) {
            $indConfig = $currentRoleConfig['indicateurs'][$indKey] ?? [];

            // Master switch : Si désactivé pour ce rôle, ni Card ni Panel ne sont rendus
            $isEnabled = $isDisplayAll ? true : (!empty($indConfig['enabled']));
            if (!$isEnabled) {
                continue;
            }

            // Exigence de session certifiante N2
            if (!empty($meta['requires_session']) && !$isSessionFormation) {
                continue;
            }

            // Extraction des données KPI associées
            $kpiData = [];
            $possibleKeys = $kpiKeyMap[$indKey] ?? [$indKey];
            foreach ($possibleKeys as $pk) {
                if (isset($kpis[$pk])) {
                    $kpiData = $kpis[$pk];
                    break;
                }
            }

            $showCard = $isDisplayAll ? true : (!empty($indConfig['show_card']));
            $showPanel = $isDisplayAll ? true : (!empty($indConfig['show_panel']));
            $showDetail = $isDisplayAll ? true : (!empty($indConfig['show_detail']));

            // Règle de couplage strict :
            // Si l'indicateur n'a ni card active ni statut opérationnel apprenant, son panel ne doit pas être orphelin
            if (!$showCard && !$showPanel) {
                continue;
            }

            $ordre = (int) ($indConfig['ordre'] ?? ($meta['ordre'] ?? 999));

            // 1. Résolution de la Card
            if ($showCard && !empty($meta['card_component'])) {
                $dashboardCards[] = [
                    'key'          => $indKey,
                    'ordre'        => $ordre,
                    'meta'         => $meta,
                    'component'    => $meta['card_component'],
                    'data'         => $kpiData,
                    'show_detail'  => $showDetail,
                    'detail_route' => $meta['detail_route'] ?? null,
                ];
            }

            // 2. Résolution du Panel
            if ($showPanel && !empty($meta['panel_component'])) {
                $panelComponent = $meta['panel_component'];
                if (is_array($panelComponent)) {
                    $panelComponent = $panelComponent[$role] ?? ($panelComponent['default'] ?? reset($panelComponent));
                }

                if ($panelComponent) {
                    $dashboardPanels[] = [
                        'key'          => $indKey,
                        'ordre'        => $ordre,
                        'meta'         => $meta,
                        'component'    => $panelComponent,
                        'data'         => $kpiData,
                        'show_detail'  => $showDetail,
                        'detail_route' => $meta['detail_route'] ?? null,
                    ];
                }
            }
        }

        // Tri des cartes et des panels selon l'ordre configuré
        usort($dashboardCards, fn($a, $b) => ($a['ordre'] ?? 999) <=> ($b['ordre'] ?? 999));
        usort($dashboardPanels, fn($a, $b) => ($a['ordre'] ?? 999) <=> ($b['ordre'] ?? 999));

        return [
            'cards'  => $dashboardCards,
            'panels' => $dashboardPanels,
            'role'   => $role,
        ];
    }
}
