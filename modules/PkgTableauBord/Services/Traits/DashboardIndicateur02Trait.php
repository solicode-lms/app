<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;

trait DashboardIndicateur02Trait
{
    /**
     * Récupère les données de l'Indicateur 02 pour le tableau de bord d'un Apprenant.
     *
     * @param int|RealisationProjet $realisationProjet
     * @return array
     */
    public function getIndicateurGatingApprenant($realisationProjet): array
    {
        if (is_numeric($realisationProjet)) {
            $realisationProjet = RealisationProjet::with(['affectationProjet', 'apprenant'])->find($realisationProjet);
        }

        if (!$realisationProjet) {
            return $this->getDefaultIndicateurGatingApprenant();
        }

        $premierEssai = is_null($realisationProjet->cache_est_valide_prototype_premier_essai)
            ? null
            : (bool) $realisationProjet->cache_est_valide_prototype_premier_essai;
        $tentatives = (int) ($realisationProjet->cache_nombre_tentatives_prototype ?? 0);
        $dateValidation = $realisationProjet->cache_date_validation_prototype;

        // Détermination du statut
        if (is_null($premierEssai) && $tentatives === 0) {
            $statut = 'Non évalué';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
            $accesProjet = 'En attente de soutenance';
            $accesBadge = 'bg-slate-100 text-slate-600';
            $isDebloque = false;
        } elseif ($premierEssai === true) {
            $statut = 'Validé (1er essai)';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
            $accesProjet = 'Débloqué (Niveau 3)';
            $accesBadge = 'text-emerald-700 font-bold';
            $isDebloque = true;
        } elseif ($premierEssai === false && !is_null($dateValidation)) {
            $statut = 'Validé (2ème essai)';
            $statutBadge = 'bg-sky-50 text-sky-700 border border-sky-200';
            $accesProjet = 'Débloqué après remédiation';
            $accesBadge = 'text-sky-700 font-bold';
            $isDebloque = true;
        } else {
            $statut = 'En remédiation';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
            $accesProjet = 'Verrouillé (Atelier requis)';
            $accesBadge = 'text-amber-700 font-bold';
            $isDebloque = false;
        }

        return [
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'acces_projet' => $accesProjet,
            'acces_badge' => $accesBadge,
            'is_debloque' => $isDebloque,
            'tentatives' => $tentatives,
            'est_valide_premier_essai' => $premierEssai,
            'date_validation' => $dateValidation ? \Carbon\Carbon::parse($dateValidation)->format('d/m/Y H:i') : null,
            'description' => 'Validation autonome des fondamentaux lors de l\'épreuve de Live Coding Niveau 2.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 02 pour le tableau de bord du Formateur.
     *
     * @param int|AffectationProjet $affectationProjet
     * @return array
     */
    public function getIndicateurGatingFormateur($affectationProjet): array
    {
        if (is_numeric($affectationProjet)) {
            $affectationProjet = AffectationProjet::with([
                'realisationProjets.apprenant.user',
                'groupe'
            ])->find($affectationProjet);
        }

        if (!$affectationProjet) {
            return $this->getDefaultIndicateurGatingFormateur();
        }

        $realisations = $affectationProjet->realisationProjets;
        $totalApprenants = $realisations->count();

        $valides1erEssai = 0;
        $evalues = 0;
        $apprenantsEnRemediation = [];

        foreach ($realisations as $rp) {
            $premierEssai = is_null($rp->cache_est_valide_prototype_premier_essai)
                ? null
                : (bool) $rp->cache_est_valide_prototype_premier_essai;
            $tentatives = (int) ($rp->cache_nombre_tentatives_prototype ?? 0);

            if (!is_null($premierEssai) || $tentatives > 0) {
                $evalues++;
                if ($premierEssai === true) {
                    $valides1erEssai++;
                } elseif ($premierEssai === false && is_null($rp->cache_date_validation_prototype)) {
                    $apprenantsEnRemediation[] = [
                        'id' => $rp->apprenant_id,
                        'nom_complet' => ($rp->apprenant->prenom ?? '') . ' ' . ($rp->apprenant->nom ?? 'Stagiaire'),
                        'avatar' => $rp->apprenant->profile_image ?? null,
                        'tentatives' => $tentatives,
                    ];
                }
            }
        }

        $tauxGating = $evalues > 0 ? round(($valides1erEssai / $evalues) * 100, 1) : 0;
        $enRemediation = count($apprenantsEnRemediation);

        // Seuils didactiques
        if ($evalues === 0) {
            $statut = 'En attente de soutenances';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
        } elseif ($tauxGating >= 80) {
            $statut = 'Excellence (>= 80%)';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } elseif ($tauxGating >= 65) {
            $statut = 'Vigilance (65-80%)';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
        } else {
            $statut = 'Alerte (< 65%)';
            $statutBadge = 'bg-rose-50 text-rose-700 border border-rose-200';
        }

        return [
            'taux_gating' => $tauxGating,
            'taux_gating_formate' => "{$tauxGating}%",
            'total_apprenants' => $totalApprenants,
            'total_evalues' => $evalues,
            'valides_premier_essai' => $valides1erEssai,
            'en_remediation' => $enRemediation,
            'ratio_texte' => "{$valides1erEssai} / {$evalues} validés au 1er passage ({$enRemediation} en remédiation)",
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'apprenants_en_remediation' => $apprenantsEnRemediation,
            'description' => 'Taux d\'assimilation autonome des prérequis techniques dès la première tentative.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 02 pour le tableau de bord du Responsable.
     *
     * @param array $groupeIds
     * @param int|null $projetId
     * @return array
     */
    public function getIndicateurGatingResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        $rpQuery = RealisationProjet::whereHas('affectationProjet.projet', function ($q) {
            $q->sessionsFormation();
        });
        if (!empty($groupeIds)) {
            $rpQuery->whereHas('affectationProjet', function ($q) use ($groupeIds) {
                $q->whereIn('groupe_id', $groupeIds);
            });
        }
        if (!empty($projetId)) {
            $rpQuery->whereHas('affectationProjet', function ($q) use ($projetId) {
                $q->where('projet_id', $projetId);
            });
        }
        $totalGlobalEvalues = (clone $rpQuery)->whereNotNull('cache_est_valide_prototype_premier_essai')->count();
        $totalGlobalValides1er = (clone $rpQuery)->where('cache_est_valide_prototype_premier_essai', true)->count();
        $tauxGlobal = $totalGlobalEvalues > 0 ? round(($totalGlobalValides1er / $totalGlobalEvalues) * 100, 1) : 75.0;

        $query = AffectationProjet::with(['groupe', 'realisationProjets', 'projet'])
            ->whereHas('projet', function ($q) {
                $q->sessionsFormation();
            });
        if (!empty($groupeIds)) {
            $query->whereIn('groupe_id', $groupeIds);
        }
        if (!empty($projetId)) {
            $query->where('projet_id', $projetId);
        }
        $affectations = $query->latest('date_debut')->take(10)->get();

        $benchmark = [];

        foreach ($affectations as $aff) {
            $evaluesGroupe = 0;
            $valides1erGroupe = 0;

            foreach ($aff->realisationProjets as $rp) {
                $premierEssai = is_null($rp->cache_est_valide_prototype_premier_essai)
                    ? null
                    : (bool) $rp->cache_est_valide_prototype_premier_essai;
                $tentatives = (int) ($rp->cache_nombre_tentatives_prototype ?? 0);

                if (!is_null($premierEssai) || $tentatives > 0) {
                    $evaluesGroupe++;
                    if ($premierEssai === true) {
                        $valides1erGroupe++;
                    }
                }
            }

            $tauxGroupe = $evaluesGroupe > 0 ? round(($valides1erGroupe / $evaluesGroupe) * 100, 1) : null;

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? ($aff->groupe->code ?? 'Promotion'),
                'taux_gating' => $tauxGroupe,
                'taux_gating_formate' => !is_null($tauxGroupe) ? "{$tauxGroupe}%" : '--',
                'valides_premier_essai' => $valides1erGroupe,
                'total_evalues' => $evaluesGroupe,
                'statut' => (!is_null($tauxGroupe) && $tauxGroupe >= 75) ? 'Conforme' : 'Vigilance',
            ];
        }

        return [
            'taux_gating_global' => $tauxGlobal,
            'taux_gating_global_formate' => "{$tauxGlobal}%",
            'total_evalues_global' => $totalGlobalEvalues,
            'valides_premier_essai_global' => $totalGlobalValides1er,
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Données par défaut apprenant.
     */
    protected function getDefaultIndicateurGatingApprenant(): array
    {
        return [
            'statut' => 'Non évalué',
            'statut_badge' => 'bg-slate-50 text-slate-700 border border-slate-200',
            'acces_projet' => 'En attente de soutenance',
            'acces_badge' => 'bg-slate-100 text-slate-600',
            'is_debloque' => false,
            'tentatives' => 0,
            'est_valide_premier_essai' => null,
            'date_validation' => null,
            'description' => 'Validation autonome des fondamentaux lors de l\'épreuve de Live Coding Niveau 2.',
        ];
    }

    /**
     * Données par défaut formateur.
     */
    protected function getDefaultIndicateurGatingFormateur(): array
    {
        return [
            'taux_gating' => 0,
            'taux_gating_formate' => '0%',
            'total_apprenants' => 0,
            'total_evalues' => 0,
            'valides_premier_essai' => 0,
            'en_remediation' => 0,
            'ratio_texte' => '0 / 0 évalués',
            'statut' => 'En attente',
            'statut_badge' => 'bg-slate-50 text-slate-700 border border-slate-200',
            'apprenants_en_remediation' => [],
            'description' => 'Taux d\'assimilation autonome des prérequis techniques dès la première tentative.',
        ];
    }
}
