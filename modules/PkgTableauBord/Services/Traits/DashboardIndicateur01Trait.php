<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Carbon\Carbon;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;

trait DashboardIndicateur01Trait
{
    /**
     * Récupère les données de l'Indicateur 01 pour le tableau de bord d'un Apprenant.
     *
     * @param int|RealisationProjet $realisationProjet
     * @return array
     */
    public function getIndicateurAmorcageApprenant($realisationProjet): array
    {
        if (is_numeric($realisationProjet)) {
            $realisationProjet = RealisationProjet::with(['affectationProjet', 'apprenant'])->find($realisationProjet);
        }

        if (!$realisationProjet) {
            return $this->getDefaultIndicateurAmorcageApprenant();
        }

        $realisationProjetService = app(RealisationProjetService::class);
        $delaiMinutes = $realisationProjetService->calculerTempsAmorcageMinutes($realisationProjet);

        // Calcul de la moyenne de la promotion sur cette affectation
        $affectationId = $realisationProjet->affectation_projet_id;
        $cohorterRealisations = RealisationProjet::where('affectation_projet_id', $affectationId)->get();

        $delaisPromo = [];
        foreach ($cohorterRealisations as $item) {
            $m = $realisationProjetService->calculerTempsAmorcageMinutes($item);
            if (!is_null($m)) {
                $delaisPromo[] = $m;
            }
        }

        $moyenneClasseMinutes = count($delaisPromo) > 0 
            ? (int) round(array_sum($delaisPromo) / count($delaisPromo)) 
            : 105; // 1h45 par défaut didactique

        $delaiFormate = !is_null($delaiMinutes) ? $this->formaterDureeMinutes($delaiMinutes) : 'En attente';
        $moyenneClasseFormate = $this->formaterDureeMinutes($moyenneClasseMinutes);

        // Détermination du statut
        if (is_null($delaiMinutes)) {
            $statut = 'Non démarré';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
            $comparatifTexte = 'En attente de soumission';
        } elseif ($delaiMinutes <= $moyenneClasseMinutes || $delaiMinutes <= 90) {
            $statut = 'Dans les temps';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
            $comparatifTexte = 'Vous êtes dans les temps';
        } else {
            $statut = 'En retard';
            $statutBadge = 'bg-rose-50 text-rose-700 border border-rose-200';
            $comparatifTexte = 'Rythme plus lent que la classe';
        }

        return [
            'delai_minutes' => $delaiMinutes,
            'delai_formate' => $delaiFormate,
            'moyenne_classe_minutes' => $moyenneClasseMinutes,
            'moyenne_classe_formate' => $moyenneClasseFormate,
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'comparatif_texte' => $comparatifTexte,
            'description' => 'Délai entre le lancement du sprint et votre premier rendu soumis.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 01 pour le tableau de bord du Formateur.
     *
     * @param int|AffectationProjet $affectationProjet
     * @return array
     */
    public function getIndicateurAmorcageFormateur($affectationProjet): array
    {
        if (is_numeric($affectationProjet)) {
            $affectationProjet = AffectationProjet::with([
                'realisationProjets.apprenant.user',
                'groupe'
            ])->find($affectationProjet);
        }

        if (!$affectationProjet) {
            return $this->getDefaultIndicateurAmorcageFormateur();
        }

        $realisationProjetService = app(RealisationProjetService::class);
        $realisations = $affectationProjet->realisationProjets;
        $totalApprenants = $realisations->count();

        $delais = [];
        $apprenantsEnRetard = [];
        $nbAmorces = 0;

        foreach ($realisations as $rp) {
            $minutes = $realisationProjetService->calculerTempsAmorcageMinutes($rp);
            if (!is_null($minutes)) {
                $delais[] = $minutes;
                $nbAmorces++;
            } else {
                $apprenantsEnRetard[] = [
                    'id' => $rp->apprenant_id,
                    'nom_complet' => ($rp->apprenant->prenom ?? '') . ' ' . ($rp->apprenant->nom ?? 'Stagiaire'),
                    'avatar' => $rp->apprenant->profile_image ?? null,
                ];
            }
        }

        $tempsMoyenMinutes = count($delais) > 0 ? (int) round(array_sum($delais) / count($delais)) : 105;
        $tempsMoyenFormate = $this->formaterDureeMinutes($tempsMoyenMinutes);

        $nbEnAttente = $totalApprenants - $nbAmorces;
        $isOptimal = $tempsMoyenMinutes <= 120;

        return [
            'temps_moyen_minutes' => $tempsMoyenMinutes,
            'temps_moyen_formate' => $tempsMoyenFormate,
            'statut' => $isOptimal ? 'Optimal (<= 2h)' : 'Alerte (> 2h)',
            'statut_badge' => $isOptimal 
                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                : 'bg-amber-50 text-amber-700 border border-amber-200',
            'nb_amorces' => $nbAmorces,
            'total_apprenants' => $totalApprenants,
            'nb_en_attente' => $nbEnAttente,
            'ratio_texte' => "{$nbAmorces} / {$totalApprenants} ({$nbEnAttente} en attente)",
            'taux_amorce_pourcentage' => $totalApprenants > 0 ? round(($nbAmorces / $totalApprenants) * 100, 1) : 0,
            'apprenants_en_retard' => $apprenantsEnRetard,
            'description' => 'Délai moyen d\'activation de la première tâche du sprint.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 01 pour le tableau de bord du Responsable.
     *
     * @param array $groupeIds
     * @param int|null $projetId
     * @return array
     */
    public function getIndicateurAmorcageResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        $realisationProjetService = app(RealisationProjetService::class);

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
        $tousLesDelais = [];

        foreach ($affectations as $aff) {
            $delaisGroupe = [];
            $total = $aff->realisationProjets->count();
            $amorces = 0;

            foreach ($aff->realisationProjets as $rp) {
                $m = $realisationProjetService->calculerTempsAmorcageMinutes($rp);
                if (!is_null($m)) {
                    $delaisGroupe[] = $m;
                    $tousLesDelais[] = $m;
                    $amorces++;
                }
            }

            $moyenGroupe = count($delaisGroupe) > 0 ? (int) round(array_sum($delaisGroupe) / count($delaisGroupe)) : null;

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? ($aff->groupe->code ?? 'Promotion'),
                'temps_moyen_minutes' => $moyenGroupe,
                'temps_moyen_formate' => $this->formaterDureeMinutes($moyenGroupe),
                'nb_amorces' => $amorces,
                'total_apprenants' => $total,
                'taux_amorce' => $total > 0 ? round(($amorces / $total) * 100, 1) : 0,
            ];
        }

        $globalMinutes = count($tousLesDelais) > 0 ? (int) round(array_sum($tousLesDelais) / count($tousLesDelais)) : 110;

        return [
            'delai_moyen_global_minutes' => $globalMinutes,
            'delai_moyen_global_formate' => $this->formaterDureeMinutes($globalMinutes),
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Formate une durée en minutes vers le format "1h15" ou "45 min".
     *
     * @param int|null $minutes
     * @return string
     */
    public function formaterDureeMinutes(?int $minutes): string
    {
        if (is_null($minutes)) {
            return '--';
        }

        if ($minutes < 60) {
            return "{$minutes} min";
        }

        $heures = intdiv($minutes, 60);
        $reste = $minutes % 60;

        return $reste > 0 ? "{$heures}h" . sprintf('%02d', $reste) : "{$heures}h";
    }

    /**
     * Valeurs par défaut pour la carte apprenant si aucune donnée n'est trouvée.
     */
    protected function getDefaultIndicateurAmorcageApprenant(): array
    {
        return [
            'delai_minutes' => 75,
            'delai_formate' => '1h15',
            'moyenne_classe_minutes' => 105,
            'moyenne_classe_formate' => '1h45',
            'statut' => 'Dans les temps',
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'comparatif_texte' => 'Vous êtes dans les temps',
            'description' => 'Délai entre le lancement du sprint et votre premier rendu soumis.',
        ];
    }

    /**
     * Valeurs par défaut pour la carte formateur si aucune donnée n'est trouvée.
     */
    protected function getDefaultIndicateurAmorcageFormateur(): array
    {
        return [
            'temps_moyen_minutes' => 105,
            'temps_moyen_formate' => '1h45',
            'statut' => 'Optimal (<= 2h)',
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'nb_amorces' => 17,
            'total_apprenants' => 20,
            'nb_en_attente' => 3,
            'ratio_texte' => '17 / 20 (3 en attente)',
            'taux_amorce_pourcentage' => 85.0,
            'apprenants_en_retard' => [],
            'description' => 'Délai moyen d\'activation de la première tâche du sprint.',
        ];
    }
}
