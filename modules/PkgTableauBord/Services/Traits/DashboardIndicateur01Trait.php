<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Carbon\Carbon;
use Modules\PkgApprenants\Models\Groupe;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationProjets\Services\RealisationProjetService;
use Modules\PkgTableauBord\Services\ParametrePedagogiqueService;

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
            : $this->parametrePedagogique()->getDelaiMoyenAmorcageFallbackMinutes();

        $delaiFormate = !is_null($delaiMinutes) ? $this->formaterDureeMinutes($delaiMinutes) : 'En attente';
        $moyenneClasseFormate = $this->formaterDureeMinutes($moyenneClasseMinutes);

        $seuilApprenantMinutes = $this->getSeuilAmorcageApprenantMinutes($realisationProjet->affectationProjet?->projet_id ?? null);

        // Détermination du statut
        if (is_null($delaiMinutes)) {
            $statut = 'Non démarré';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
            $comparatifTexte = 'En attente de soumission';
        } elseif ($delaiMinutes <= $moyenneClasseMinutes || $delaiMinutes <= $seuilApprenantMinutes) {
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
                    'prenom' => $rp->apprenant->prenom ?? '',
                    'nom' => $rp->apprenant->nom ?? 'Stagiaire',
                    'matricule' => $rp->apprenant->matricule ?? null,
                    'avatar' => $rp->apprenant->profile_image ?? null,
                ];
            }
        }

        $tempsMoyenMinutes = count($delais) > 0 
            ? (int) round(array_sum($delais) / count($delais)) 
            : $this->parametrePedagogique()->getDelaiMoyenAmorcageFallbackMinutes($affectationProjet->projet_id ?? null);
        $tempsMoyenFormate = $this->formaterDureeMinutes($tempsMoyenMinutes);

        $nbEnAttente = $totalApprenants - $nbAmorces;
        $seuilOptimalMinutes = $this->getSeuilAmorcageOptimalGroupeMinutes($affectationProjet->projet_id ?? null);
        $seuilOptimalFormate = $this->formaterDureeMinutes($seuilOptimalMinutes);
        $isOptimal = $tempsMoyenMinutes <= $seuilOptimalMinutes;

        return [
            'temps_moyen_minutes' => $tempsMoyenMinutes,
            'temps_moyen_formate' => $tempsMoyenFormate,
            'seuil_optimal_minutes' => $seuilOptimalMinutes,
            'seuil_optimal_formate' => $seuilOptimalFormate,
            'statut' => $isOptimal ? "Optimal (<= {$seuilOptimalFormate})" : "Alerte (> {$seuilOptimalFormate})",
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
        $delaiFallback = $this->parametrePedagogique()->getDelaiMoyenAmorcageFallbackMinutes();
        return [
            'delai_minutes' => 75,
            'delai_formate' => '1h15',
            'moyenne_classe_minutes' => $delaiFallback,
            'moyenne_classe_formate' => $this->formaterDureeMinutes($delaiFallback),
            'statut' => 'Dans les temps',
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'comparatif_texte' => 'Vous êtes dans les temps',
            'description' => 'Délai entre le lancement du sprint et votre premier rendu soumis.',
        ];
    }

    /**
     * Accesseur au service central des paramètres pédagogiques.
     *
     * @return ParametrePedagogiqueService
     */
    public function parametrePedagogique(): ParametrePedagogiqueService
    {
        return app(ParametrePedagogiqueService::class);
    }

    /**
     * Seuil didactique d'amorçage optimal pour un groupe (en minutes).
     * Délégué au ParametrePedagogiqueService.
     *
     * @param int|null $projetId
     * @return int
     */
    public function getSeuilAmorcageOptimalGroupeMinutes(?int $projetId = null): int
    {
        return $this->parametrePedagogique()->getSeuilAmorcageOptimalGroupeMinutes($projetId);
    }

    /**
     * Seuil didactique d'amorçage individuel pour un apprenant (en minutes).
     * Délégué au ParametrePedagogiqueService.
     *
     * @param int|null $projetId
     * @return int
     */
    public function getSeuilAmorcageApprenantMinutes(?int $projetId = null): int
    {
        return $this->parametrePedagogique()->getSeuilAmorcageApprenantMinutes($projetId);
    }

    /**
     * Valeurs par défaut pour la carte formateur si aucune donnée n'est trouvée.
     */
    protected function getDefaultIndicateurAmorcageFormateur(): array
    {
        $seuilOptimalMinutes = $this->getSeuilAmorcageOptimalGroupeMinutes();
        $seuilOptimalFormate = $this->formaterDureeMinutes($seuilOptimalMinutes);
        $delaiFallback = $this->parametrePedagogique()->getDelaiMoyenAmorcageFallbackMinutes();

        return [
            'temps_moyen_minutes' => $delaiFallback,
            'temps_moyen_formate' => $this->formaterDureeMinutes($delaiFallback),
            'seuil_optimal_minutes' => $seuilOptimalMinutes,
            'seuil_optimal_formate' => $seuilOptimalFormate,
            'statut' => "Optimal (<= {$seuilOptimalFormate})",
            'statut_badge' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'nb_amorces' => 17,
            'total_apprenants' => 20,
            'nb_en_attente' => 3,
            'ratio_texte' => '17 / 20 (3 en attente)',
            'taux_amorce_pourcentage' => 85.0,
            'apprenants_en_retard' => [
                [
                    'id' => 1,
                    'nom_complet' => 'Amine Mansouri',
                    'prenom' => 'Amine',
                    'nom' => 'Mansouri',
                    'matricule' => 'STG-0412',
                    'avatar' => null,
                ],
                [
                    'id' => 2,
                    'nom_complet' => 'Sara Bennani',
                    'prenom' => 'Sara',
                    'nom' => 'Bennani',
                    'matricule' => 'STG-0418',
                    'avatar' => null,
                ],
                [
                    'id' => 3,
                    'nom_complet' => 'Nabil Tazi',
                    'prenom' => 'Nabil',
                    'nom' => 'Tazi',
                    'matricule' => 'STG-0425',
                    'avatar' => null,
                ],
            ],
            'description' => 'Délai moyen d\'activation de la première tâche du sprint.',
        ];
    }

    /**
     * Récupère l'ensemble des données analytiques détaillées pour la page de détail de l'Indicateur 01.
     *
     * @param array $filters
     * @return array
     */
    public function getIndicateurAmorcageDetail(array $filters = []): array
    {
        $realisationProjetService = app(RealisationProjetService::class);
        $seuilOptimalMinutes = $this->getSeuilAmorcageOptimalGroupeMinutes($filters['projet_id'] ?? null);

        $query = AffectationProjet::with([
            'groupe.filiere',
            'projet.formateur',
            'realisationProjets.apprenant.user',
            'realisationProjets.realisationTaches.tache'
        ]);

        if (!empty($filters['projet_id'])) {
            $query->where('projet_id', $filters['projet_id']);
        } elseif (($filters['type_projet'] ?? 'sessions') === 'sessions') {
            $query->whereHas('projet', fn($q) => $q->sessionsFormation());
        }

        if (!empty($filters['groupe_id'])) {
            $query->where('groupe_id', $filters['groupe_id']);
        } elseif (!empty($filters['groupe_ids'])) {
            $query->whereIn('groupe_id', $filters['groupe_ids']);
        } elseif (!empty($filters['filiere_id'])) {
            $query->whereHas('groupe', fn($q) => $q->where('filiere_id', $filters['filiere_id']));
        }

        if (!empty($filters['formateur_id'])) {
            $query->whereHas('projet', fn($p) => $p->where('formateur_id', $filters['formateur_id']));
        }

        $affectations = $query->latest('date_debut')->get();

        $apprenants = [];
        $delais = [];
        $tranches = [
            'eclair' => 0,    // < 60 min
            'optimal' => 0,   // 60 - 120 min
            'tardif' => 0,    // 120 - 240 min
            'critique' => 0,  // > 240 min ou non amorcé
        ];
        $benchmarkGroupes = [];

        foreach ($affectations as $aff) {
            $groupeKey = $aff->groupe_id;
            $groupeCode = $aff->groupe->code ?? ('Groupe ' . $aff->groupe_id);
            if (!isset($benchmarkGroupes[$groupeKey])) {
                $benchmarkGroupes[$groupeKey] = [
                    'groupe_id' => $groupeKey,
                    'groupe_code' => $groupeCode,
                    'groupe_nom' => $aff->groupe->nom ?? $groupeCode,
                    'filiere_code' => $aff->groupe->filiere->code ?? '-',
                    'total_apprenants' => 0,
                    'nb_amorces' => 0,
                    'delais' => [],
                ];
            }

            $dateLancement = $aff->cache_date_lancement ?? $aff->date_debut;

            foreach ($aff->realisationProjets as $rp) {
                $apprenant = $rp->apprenant;
                if (!$apprenant) continue;

                $benchmarkGroupes[$groupeKey]['total_apprenants']++;

                $minutes = $realisationProjetService->calculerTempsAmorcageMinutes($rp);
                $dateSoumission = $rp->cache_date_premiere_soumission;
                $premiereTache = $rp->realisationTaches->sortBy('created_at')->first()?->tache?->titre;

                $isAmorce = !is_null($minutes);

                if ($isAmorce) {
                    $delais[] = $minutes;
                    $benchmarkGroupes[$groupeKey]['delais'][] = $minutes;
                    $benchmarkGroupes[$groupeKey]['nb_amorces']++;

                    if ($minutes < 60) {
                        $tranches['eclair']++;
                        $statutCode = 'eclair';
                        $statutLabel = 'Démarrage éclair';
                        $statutBadge = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                    } elseif ($minutes <= $seuilOptimalMinutes) {
                        $tranches['optimal']++;
                        $statutCode = 'optimal';
                        $statutLabel = 'Dans les temps';
                        $statutBadge = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                    } elseif ($minutes <= 240) {
                        $tranches['tardif']++;
                        $statutCode = 'tardif';
                        $statutLabel = 'Démarrage tardif';
                        $statutBadge = 'bg-amber-50 text-amber-800 border-amber-200';
                    } else {
                        $tranches['critique']++;
                        $statutCode = 'critique';
                        $statutLabel = 'Retard critique';
                        $statutBadge = 'bg-rose-50 text-rose-800 border-rose-200';
                    }
                } else {
                    $tranches['critique']++;
                    $statutCode = 'non_amorce';
                    $statutLabel = 'Non amorcé';
                    $statutBadge = 'bg-rose-50 text-rose-700 border-rose-200 animate-pulse';
                }

                $apprenants[] = [
                    'id' => $apprenant->id,
                    'user_id' => $apprenant->user_id,
                    'nom_complet' => ($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? 'Stagiaire'),
                    'prenom' => $apprenant->prenom ?? '',
                    'nom' => $apprenant->nom ?? 'Stagiaire',
                    'matricule' => $apprenant->matricule ?? '-',
                    'email' => $apprenant->user->email ?? null,
                    'avatar' => $apprenant->profile_image ?? null,
                    'groupe_code' => $groupeCode,
                    'projet_titre' => $aff->projet->titre ?? 'Projet',
                    'date_lancement' => $dateLancement ? Carbon::parse($dateLancement)->format('d/m/Y H:i') : null,
                    'date_premiere_soumission' => $dateSoumission ? Carbon::parse($dateSoumission)->format('d/m/Y H:i') : null,
                    'delai_minutes' => $minutes,
                    'delai_formate' => $this->formaterDureeMinutes($minutes),
                    'statut_code' => $statutCode,
                    'statut_label' => $statutLabel,
                    'statut_badge' => $statutBadge,
                    'premiere_tache' => $premiereTache ?? 'Tâche initiale',
                ];
            }
        }

        $totalApprenants = count($apprenants);
        $nbAmorces = count($delais);
        $nbNonAmorces = $totalApprenants - $nbAmorces;
        $tauxAmorce = $totalApprenants > 0 ? round(($nbAmorces / $totalApprenants) * 100, 1) : 0;

        $delaiMoyenMinutes = $nbAmorces > 0 ? (int) round(array_sum($delais) / $nbAmorces) : null;
        $delaiMinMinutes = $nbAmorces > 0 ? min($delais) : null;
        $delaiMaxMinutes = $nbAmorces > 0 ? max($delais) : null;

        // Calcul de la médiane
        $delaiMedianMinutes = null;
        if ($nbAmorces > 0) {
            $delaisTries = $delais;
            sort($delaisTries);
            $middle = (int) floor($nbAmorces / 2);
            $delaiMedianMinutes = ($nbAmorces % 2 === 0) 
                ? (int) round(($delaisTries[$middle - 1] + $delaisTries[$middle]) / 2) 
                : $delaisTries[$middle];
        }

        // Apprenant le plus rapide
        $apprenantRapide = null;
        if ($delaiMinMinutes !== null) {
            foreach ($apprenants as $appr) {
                if ($appr['delai_minutes'] === $delaiMinMinutes) {
                    $apprenantRapide = $appr;
                    break;
                }
            }
        }

        // Finalisation benchmark groupes
        $benchmark = [];
        foreach ($benchmarkGroupes as $bg) {
            $cDelais = count($bg['delais']);
            $m = $cDelais > 0 ? (int) round(array_sum($bg['delais']) / $cDelais) : null;
            $benchmark[] = [
                'groupe_id' => $bg['groupe_id'],
                'groupe_code' => $bg['groupe_code'],
                'groupe_nom' => $bg['groupe_nom'],
                'filiere_code' => $bg['filiere_code'],
                'total_apprenants' => $bg['total_apprenants'],
                'nb_amorces' => $bg['nb_amorces'],
                'taux_amorce' => $bg['total_apprenants'] > 0 ? round(($bg['nb_amorces'] / $bg['total_apprenants']) * 100, 1) : 0,
                'delai_moyen_minutes' => $m,
                'delai_moyen_formate' => $this->formaterDureeMinutes($m),
            ];
        }

        // Recommandations pédagogiques contextuelles
        $recommandations = [];
        if ($tauxAmorce < 75) {
            $recommandations[] = [
                'type' => 'alerte',
                'titre' => 'Déclenchement d\'un déblocage technique collectif',
                'message' => 'Moins de 75% des apprenants ont amorcé leur projet. Il est recommandé d\'animer un point d\'étape de 15 minutes pour vérifier la configuration des environnements locaux et lever les ambiguïtés sur le premier livrable.',
                'icone' => 'fa-triangle-exclamation',
                'classe' => 'bg-rose-50 border-rose-200 text-rose-900',
            ];
        } elseif ($tauxAmorce < 90) {
            $recommandations[] = [
                'type' => 'vigilance',
                'titre' => 'Accompagnement individualisé des retardataires',
                'message' => 'Quelques apprenants n\'ont pas encore soumis de premier rendu. Une relance individuelle ou un binômage d\'amorce permettrait de résorber la latence sans ralentir le reste du groupe.',
                'icone' => 'fa-bell',
                'classe' => 'bg-amber-50 border-amber-200 text-amber-900',
            ];
        } else {
            $recommandations[] = [
                'type' => 'succes',
                'titre' => 'Dynamique d\'amorce optimale',
                'message' => 'Excellent démarrage collectif (> 90% amorcés). La phase de cadrage initiale est assimilée et le groupe est entré en autonomie de production.',
                'icone' => 'fa-circle-check',
                'classe' => 'bg-emerald-50 border-emerald-200 text-emerald-900',
            ];
        }

        return [
            'total_apprenants' => $totalApprenants,
            'nb_amorces' => $nbAmorces,
            'nb_non_amorces' => $nbNonAmorces,
            'taux_amorce' => $tauxAmorce,
            'delai_moyen_minutes' => $delaiMoyenMinutes,
            'delai_moyen_formate' => $this->formaterDureeMinutes($delaiMoyenMinutes),
            'delai_median_formate' => $this->formaterDureeMinutes($delaiMedianMinutes),
            'delai_min_formate' => $this->formaterDureeMinutes($delaiMinMinutes),
            'delai_max_formate' => $this->formaterDureeMinutes($delaiMaxMinutes),
            'apprenant_rapide' => $apprenantRapide,
            'seuil_optimal_minutes' => $seuilOptimalMinutes,
            'seuil_optimal_formate' => $this->formaterDureeMinutes($seuilOptimalMinutes),
            'tranches' => $tranches,
            'apprenants' => $apprenants,
            'benchmark' => $benchmark,
            'recommandations' => $recommandations,
        ];
    }
}

