<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;

trait DashboardIndicateur03Trait
{
    /**
     * Récupère les données de l'Indicateur 03 pour le tableau de bord d'un Apprenant.
     *
     * @param int|RealisationProjet $realisationProjet
     * @return array
     */
    public function getIndicateurRevisionApprenant($realisationProjet): array
    {
        if (is_numeric($realisationProjet)) {
            $realisationProjet = RealisationProjet::with(['realisationTaches.tache'])->find($realisationProjet);
        }

        if (!$realisationProjet) {
            return $this->getDefaultIndicateurRevisionApprenant();
        }

        $realisations = $realisationProjet->realisationTaches;
        $totalTaches = $realisations->count();

        if ($totalTaches === 0) {
            return $this->getDefaultIndicateurRevisionApprenant();
        }

        $totalRejets = 0;
        $sansRevision = 0;

        foreach ($realisations as $rt) {
            $nb = (int) ($rt->cache_nombre_revisions ?? 0);
            $totalRejets += $nb;
            if ($nb === 0) {
                $sansRevision++;
            }
        }

        $frequence = round($totalRejets / $totalTaches, 1);
        $tauxConforme = round(($sansRevision / $totalTaches) * 100, 1);

        // Seuils didactiques
        if ($frequence < 0.5) {
            $statut = 'Qualité initiale élevée';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } elseif ($frequence <= 1.2) {
            $statut = 'Ajustements en cours';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
        } else {
            $statut = 'Réalignement requis';
            $statutBadge = 'bg-rose-50 text-rose-700 border border-rose-200';
        }

        return [
            'frequence' => $frequence,
            'frequence_formatee' => "{$frequence} reprise / tâche",
            'total_rejets' => $totalRejets,
            'total_taches' => $totalTaches,
            'taches_sans_reprise' => $sansRevision,
            'taux_premier_jet' => $tauxConforme,
            'taux_premier_jet_formate' => "{$tauxConforme}% de réussite directe",
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'description' => 'Mesure de la stabilité du code et de l\'autonomie dès le premier rendu soumis.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 03 pour le tableau de bord du Formateur.
     *
     * @param int|AffectationProjet $affectationProjet
     * @return array
     */
    public function getIndicateurRevisionFormateur($affectationProjet): array
    {
        if (is_numeric($affectationProjet)) {
            $affectationProjet = AffectationProjet::with([
                'realisationProjets.realisationTaches.tache',
                'groupe'
            ])->find($affectationProjet);
        }

        if (!$affectationProjet) {
            return $this->getDefaultIndicateurRevisionFormateur();
        }

        $toutesLesRealisations = [];
        $tachesGrouped = [];

        foreach ($affectationProjet->realisationProjets as $rp) {
            foreach ($rp->realisationTaches as $rt) {
                $toutesLesRealisations[] = $rt;

                $tacheId = $rt->tache_id;
                if (!isset($tachesGrouped[$tacheId])) {
                    $tachesGrouped[$tacheId] = [
                        'tache_id' => $tacheId,
                        'titre' => $rt->tache->titre ?? "Tâche #{$tacheId}",
                        'revisions_totales' => 0,
                        'apprenants_avec_reprise' => 0,
                        'total_evaluations' => 0,
                    ];
                }

                $revisions = (int) ($rt->cache_nombre_revisions ?? 0);
                $tachesGrouped[$tacheId]['revisions_totales'] += $revisions;
                $tachesGrouped[$tacheId]['total_evaluations']++;
                if ($revisions > 0) {
                    $tachesGrouped[$tacheId]['apprenants_avec_reprise']++;
                }
            }
        }

        $totalTaches = count($toutesLesRealisations);
        if ($totalTaches === 0) {
            return $this->getDefaultIndicateurRevisionFormateur();
        }

        $totalRejets = 0;
        $sansRevision = 0;

        foreach ($toutesLesRealisations as $rt) {
            $nb = (int) ($rt->cache_nombre_revisions ?? 0);
            $totalRejets += $nb;
            if ($nb === 0) {
                $sansRevision++;
            }
        }

        $frequence = round($totalRejets / $totalTaches, 1);
        $tauxConforme = round(($sansRevision / $totalTaches) * 100, 1);

        // Tri du Top 3 des tâches nécessitant un réalignement
        usort($tachesGrouped, function ($a, $b) {
            return $b['revisions_totales'] <=> $a['revisions_totales'];
        });

        $top3Taches = [];
        foreach (array_slice($tachesGrouped, 0, 3) as $item) {
            $moyen = $item['total_evaluations'] > 0 
                ? round($item['revisions_totales'] / $item['total_evaluations'], 1) 
                : 0.0;
            
            $top3Taches[] = [
                'tache_id' => $item['tache_id'],
                'titre' => $item['titre'],
                'frequence_moyenne' => $moyen,
                'frequence_formatee' => "{$moyen} itér.",
                'apprenants_touches' => $item['apprenants_avec_reprise'],
                'explication' => $item['apprenants_avec_reprise'] > 0
                    ? "{$item['apprenants_avec_reprise']} apprenants ont nécessité au moins 1 reprise."
                    : "Bonne appropriation dès la première soumission.",
            ];
        }

        // Statut didactique
        if ($frequence < 0.5) {
            $statut = 'Excellence didactique';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } elseif ($frequence <= 1.2) {
            $statut = 'Vigilance';
            $statutBadge = 'bg-amber-50 text-amber-700 border border-amber-200';
        } else {
            $statut = 'Alerte Tâtonnement';
            $statutBadge = 'bg-rose-50 text-rose-700 border border-rose-200';
        }

        return [
            'frequence' => $frequence,
            'frequence_formatee' => "{$frequence} reprise / tâche",
            'total_rejets' => $totalRejets,
            'total_taches' => $totalTaches,
            'taux_premier_jet' => $tauxConforme,
            'taux_premier_jet_formate' => "{$tauxConforme}% des tâches validées sans reprise",
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'top_taches' => $top3Taches,
            'description' => 'Suivi de la stabilité du code et détection des consignes pédagogiques ambiguës.',
        ];
    }

    /**
     * Récupère les données de l'Indicateur 03 pour le tableau de bord du Responsable.
     *
     * @param array $groupeIds
     * @param int|null $projetId
     * @return array
     */
    public function getIndicateurRevisionResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        $query = AffectationProjet::with(['groupe', 'realisationProjets.realisationTaches', 'projet'])
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

        $toutesLesRevisions = [];
        $benchmark = [];

        foreach ($affectations as $aff) {
            $rejetsGroupe = 0;
            $tachesGroupe = 0;
            $sansRevisionGroupe = 0;

            foreach ($aff->realisationProjets as $rp) {
                foreach ($rp->realisationTaches as $rt) {
                    $nb = (int) ($rt->cache_nombre_revisions ?? 0);
                    $rejetsGroupe += $nb;
                    $toutesLesRevisions[] = $nb;
                    $tachesGroupe++;
                    if ($nb === 0) {
                        $sansRevisionGroupe++;
                    }
                }
            }

            $freqGroupe = $tachesGroupe > 0 ? round($rejetsGroupe / $tachesGroupe, 1) : 0.0;
            $tauxDirect = $tachesGroupe > 0 ? round(($sansRevisionGroupe / $tachesGroupe) * 100, 1) : 100.0;

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? ($aff->groupe->code ?? 'Promotion'),
                'frequence_revision' => $freqGroupe,
                'frequence_formatee' => "{$freqGroupe} itér.",
                'taux_premier_jet' => $tauxDirect,
                'taux_premier_jet_formate' => "{$tauxDirect}%",
                'statut_stabilite' => $freqGroupe <= 1.2 ? 'Conforme' : 'Vigilance',
                'statut_badge' => $freqGroupe <= 1.2 
                    ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                    : 'bg-amber-50 text-amber-700 border border-amber-200',
            ];
        }

        $totalTachesGlobal = count($toutesLesRevisions);
        $globalFreq = $totalTachesGlobal > 0 ? round(array_sum($toutesLesRevisions) / $totalTachesGlobal, 1) : 0.5;

        return [
            'frequence_globale' => $globalFreq,
            'frequence_globale_formatee' => "{$globalFreq} itér. / tâche",
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Données par défaut pour l'apprenant.
     */
    protected function getDefaultIndicateurRevisionApprenant(): array
    {
        return [
            'frequence' => 0.0,
            'frequence_formatee' => '0.0 reprise / tâche',
            'total_rejets' => 0,
            'total_taches' => 0,
            'taches_sans_reprise' => 0,
            'taux_premier_jet' => 100.0,
            'taux_premier_jet_formate' => '100% de réussite directe',
            'statut' => 'Qualité initiale élevée',
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'description' => 'Mesure de la stabilité du code et de l\'autonomie dès le premier rendu soumis.',
        ];
    }

    /**
     * Données par défaut pour le formateur.
     */
    protected function getDefaultIndicateurRevisionFormateur(): array
    {
        return [
            'frequence' => 0.0,
            'frequence_formatee' => '0.0 reprise / tâche',
            'total_rejets' => 0,
            'total_taches' => 0,
            'taux_premier_jet' => 100.0,
            'taux_premier_jet_formate' => '100% des tâches validées sans reprise',
            'statut' => 'Excellence didactique',
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'top_taches' => [],
            'description' => 'Suivi de la stabilité du code et détection des consignes pédagogiques ambiguës.',
        ];
    }
}
