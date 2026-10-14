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
            : null;

        $delaiFormate = !is_null($delaiMinutes) ? $this->formaterDureeMinutes($delaiMinutes) : 'En attente';
        $moyenneClasseFormate = $this->formaterDureeMinutes($moyenneClasseMinutes);

        $seuilApprenantMinutes = $this->getSeuilAmorcageApprenantMinutes($realisationProjet->affectationProjet?->projet_id ?? null);

        // Détermination du statut
        if (is_null($delaiMinutes)) {
            $statut = 'Non démarré';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
            $comparatifTexte = 'En attente de démarrage';
        } elseif (is_null($moyenneClasseMinutes)) {
            $statut = 'Dans les temps';
            $statutBadge = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
            $comparatifTexte = 'Premier du groupe';
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
                'realisationProjets.realisationTaches.etatRealisationTache.workflowTache',
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

        $dateLancement = $affectationProjet->cache_date_lancement ?? $affectationProjet->date_debut;
        $dateFinSeance = $this->parametrePedagogique()->determinerFinSeanceLancement($dateLancement, $affectationProjet->projet_id ?? null);

        $delaisPresents = [];
        $apprenantsEnRetard = [];
        $nbPresentsAmorces = 0;
        $nbDemarrageAvecAbsence = 0;

        foreach ($realisations as $rp) {
            $minutes = $realisationProjetService->calculerTempsAmorcageMinutes($rp);
            $dateEnCours = $this->determinerDatePremiereTacheEnCours($rp);

            if (!is_null($minutes)) {
                if ($dateEnCours && $dateEnCours->lte($dateFinSeance)) {
                    // Présent lors de la séance de lancement
                    $delaisPresents[] = $minutes;
                    $nbPresentsAmorces++;
                } else {
                    // Démarrage avec absence (exclu de la moyenne)
                    $nbDemarrageAvecAbsence++;
                }
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

        $tempsMoyenMinutes = count($delaisPresents) > 0 
            ? (int) round(array_sum($delaisPresents) / count($delaisPresents)) 
            : null;
        $tempsMoyenFormate = $this->formaterDureeMinutes($tempsMoyenMinutes);

        $nbEnAttente = $totalApprenants - ($nbPresentsAmorces + $nbDemarrageAvecAbsence);
        $seuilOptimalMinutes = $this->getSeuilAmorcageOptimalGroupeMinutes($affectationProjet->projet_id ?? null);
        $seuilOptimalFormate = $this->formaterDureeMinutes($seuilOptimalMinutes);

        if (is_null($tempsMoyenMinutes)) {
            $statut = 'En attente';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
        } else {
            $isOptimal = $tempsMoyenMinutes <= $seuilOptimalMinutes;
            $statut = $isOptimal ? "Optimal (<= {$seuilOptimalFormate})" : "Alerte (> {$seuilOptimalFormate})";
            $statutBadge = $isOptimal 
                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                : 'bg-amber-50 text-amber-700 border border-amber-200';
        }

        return [
            'temps_moyen_minutes' => $tempsMoyenMinutes,
            'temps_moyen_formate' => $tempsMoyenFormate,
            'seuil_optimal_minutes' => $seuilOptimalMinutes,
            'seuil_optimal_formate' => $seuilOptimalFormate,
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'nb_amorces' => $nbPresentsAmorces,
            'nb_demarrage_avec_absence' => $nbDemarrageAvecAbsence,
            'total_apprenants' => $totalApprenants,
            'nb_en_attente' => $nbEnAttente,
            'ratio_texte' => "{$nbPresentsAmorces} / {$totalApprenants} ({$nbEnAttente} en attente)",
            'taux_amorce_pourcentage' => $totalApprenants > 0 ? round((($nbPresentsAmorces + $nbDemarrageAvecAbsence) / $totalApprenants) * 100, 1) : 0,
            'apprenants_en_retard' => $apprenantsEnRetard,
            'description' => 'Délai moyen d\'activation en séance par les apprenants présents.',
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

        $query = AffectationProjet::with([
                'groupe', 
                'realisationProjets.realisationTaches.etatRealisationTache.workflowTache',
                'realisationProjets.apprenant.user', 
                'projet'
            ])
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
        $tousLesDelaisPresents = [];
        $totalApprenantsGlobal = 0;
        $nbAmorcesGlobalPresents = 0;
        $nbDemarrageAvecAbsenceGlobal = 0;
        $apprenantsEnRetard = [];

        foreach ($affectations as $aff) {
            $delaisGroupe = [];
            $total = $aff->realisationProjets->count();
            $totalApprenantsGlobal += $total;
            $amorcesPresents = 0;
            $absenceSession = 0;

            $dateLancement = $aff->cache_date_lancement ?? $aff->date_debut;
            $dateFinSeance = $this->parametrePedagogique()->determinerFinSeanceLancement($dateLancement, $aff->projet_id ?? null);

            foreach ($aff->realisationProjets as $rp) {
                $m = $realisationProjetService->calculerTempsAmorcageMinutes($rp);
                $dateEnCours = $this->determinerDatePremiereTacheEnCours($rp);

                if (!is_null($m)) {
                    if ($dateEnCours && $dateEnCours->lte($dateFinSeance)) {
                        $delaisGroupe[] = $m;
                        $tousLesDelaisPresents[] = $m;
                        $amorcesPresents++;
                    } else {
                        $absenceSession++;
                        $nbDemarrageAvecAbsenceGlobal++;
                    }
                } else {
                    $apprenant = $rp->apprenant;
                    if ($apprenant) {
                        $apprenantsEnRetard[] = [
                            'id' => $apprenant->id,
                            'nom_complet' => ($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? 'Stagiaire'),
                            'prenom' => $apprenant->prenom ?? '',
                            'nom' => $apprenant->nom ?? 'Stagiaire',
                            'matricule' => $apprenant->matricule ?? null,
                            'avatar' => $apprenant->profile_image ?? null,
                            'groupe_code' => $aff->groupe->code ?? 'Groupe',
                            'projet_titre' => $aff->projet->titre ?? 'Projet',
                        ];
                    }
                }
            }
            $nbAmorcesGlobalPresents += $amorcesPresents;

            $moyenGroupe = count($delaisGroupe) > 0 ? (int) round(array_sum($delaisGroupe) / count($delaisGroupe)) : null;

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'session_titre' => $aff->projet->titre ?? 'Session',
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? ($aff->groupe->code ?? 'Promotion'),
                'temps_moyen_minutes' => $moyenGroupe,
                'temps_moyen_formate' => $this->formaterDureeMinutes($moyenGroupe),
                'nb_amorces' => $amorcesPresents,
                'nb_demarrage_avec_absence' => $absenceSession,
                'total_apprenants' => $total,
                'taux_amorce' => $total > 0 ? round((($amorcesPresents + $absenceSession) / $total) * 100, 1) : 0,
            ];
        }

        $globalMinutes = count($tousLesDelaisPresents) > 0 
            ? (int) round(array_sum($tousLesDelaisPresents) / count($tousLesDelaisPresents)) 
            : null;
        $seuilOptimalMinutes = $this->getSeuilAmorcageOptimalGroupeMinutes($projetId);
        $seuilOptimalFormate = $this->formaterDureeMinutes($seuilOptimalMinutes);
        $tauxGlobal = $totalApprenantsGlobal > 0 
            ? round((($nbAmorcesGlobalPresents + $nbDemarrageAvecAbsenceGlobal) / $totalApprenantsGlobal) * 100, 1) 
            : 0;

        if (is_null($globalMinutes)) {
            $statut = 'En attente';
            $statutBadge = 'bg-slate-50 text-slate-700 border border-slate-200';
        } else {
            $isOptimal = $globalMinutes <= $seuilOptimalMinutes;
            $statut = $isOptimal ? "Optimal (<= {$seuilOptimalFormate})" : "Alerte (> {$seuilOptimalFormate})";
            $statutBadge = $isOptimal 
                ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                : 'bg-amber-50 text-amber-700 border border-amber-200';
        }

        return [
            'delai_moyen_global_minutes' => $globalMinutes,
            'delai_moyen_global_formate' => $this->formaterDureeMinutes($globalMinutes),
            'temps_moyen_formate' => $this->formaterDureeMinutes($globalMinutes),
            'total_apprenants' => $totalApprenantsGlobal,
            'nb_amorces' => $nbAmorcesGlobalPresents,
            'nb_demarrage_avec_absence' => $nbDemarrageAvecAbsenceGlobal,
            'nb_en_attente' => $totalApprenantsGlobal - ($nbAmorcesGlobalPresents + $nbDemarrageAvecAbsenceGlobal),
            'taux_amorce_pourcentage' => $tauxGlobal,
            'seuil_optimal_minutes' => $seuilOptimalMinutes,
            'seuil_optimal_formate' => $seuilOptimalFormate,
            'statut' => $statut,
            'statut_badge' => $statutBadge,
            'benchmark' => $benchmark,
            'apprenants_en_retard' => $apprenantsEnRetard,
            'formule_calcul' => [
                'formule' => 'T_amorçage = Date 1re soumission - Date lancement officiel (séance)',
                'seuil' => $seuilOptimalFormate,
                'interpretation' => 'Calculé uniquement sur les apprenants présents en séance de lancement (1ère tâche passée en cours avant la fin de séance).'
            ],
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

        return [
            'temps_moyen_minutes' => null,
            'temps_moyen_formate' => '--',
            'seuil_optimal_minutes' => $seuilOptimalMinutes,
            'seuil_optimal_formate' => $seuilOptimalFormate,
            'statut' => 'En attente',
            'statut_badge' => 'bg-slate-50 text-slate-700 border border-slate-200',
            'nb_amorces' => 0,
            'nb_demarrage_avec_absence' => 0,
            'total_apprenants' => 0,
            'nb_en_attente' => 0,
            'ratio_texte' => '0 / 0',
            'taux_amorce_pourcentage' => 0,
            'apprenants_en_retard' => [],
            'description' => 'Délai moyen d\'activation en séance par les apprenants présents.',
        ];
    }

    /**
     * Détermine la date/heure à laquelle un apprenant a mis sa première tâche en cours pour un projet.
     *
     * @param RealisationProjet $rp
     * @return \Carbon\Carbon|null
     */
    /**
     * Détermine la date/heure à laquelle un apprenant a mis sa première tâche en cours pour un projet.
     * Privilégie l'horodatage réel (Historique, transitions d'état) et garantit la présence d'heures et minutes.
     *
     * @param RealisationProjet $rp
     * @return \Carbon\Carbon|null
     */
    protected function determinerDatePremiereTacheEnCours(RealisationProjet $rp): ?\Carbon\Carbon
    {
        // 1. Recherche dans l'Historique des transitions de tâches (timestamp exact à la seconde)
        $rtIds = $rp->realisationTaches->pluck('id')->toArray();
        $dateHisto = null;
        if (!empty($rtIds)) {
            $premierHisto = \Modules\PkgRealisationTache\Models\HistoriqueRealisationTache::whereIn('realisation_tache_id', $rtIds)
                ->where(function ($q) {
                    $q->where('changement', 'like', '%En cours%')
                      ->orWhere('changement', 'like', '%IN_PROGRESS%');
                })
                ->min('created_at');
            if ($premierHisto) {
                $dateHisto = \Carbon\Carbon::parse($premierHisto);
            }
        }

        // 2. Recherche parmi les tâches commencées avec heure précise (différente de minuit 00:00:00)
        $dateTachePrecise = null;
        foreach ($rp->realisationTaches as $rt) {
            $code = optional($rt->etatRealisationTache?->workflowTache)->code;
            if ($code && $code !== 'TODO') {
                if ($rt->dateDebut) {
                    $c = \Carbon\Carbon::parse($rt->dateDebut);
                    if ($c->format('H:i:s') !== '00:00:00') {
                        if (is_null($dateTachePrecise) || $c->lt($dateTachePrecise)) {
                            $dateTachePrecise = $c;
                        }
                    }
                }
            }
        }

        // 3. Date de première soumission (borne supérieure absolue : un travail est forcément entamé au plus tard lors de son premier rendu)
        $dateSoumission = $rp->cache_date_premiere_soumission 
            ? \Carbon\Carbon::parse($rp->cache_date_premiere_soumission) 
            : null;

        // Choix de la date la plus précoce et précise
        $candidates = array_filter([$dateHisto, $dateTachePrecise, $dateSoumission]);
        if (!empty($candidates)) {
            usort($candidates, fn($a, $b) => $a->timestamp <=> $b->timestamp);
            return $candidates[0];
        }

        // 4. Fallback si aucune heure précise n'a pu être trouvée
        $dateFallback = null;
        foreach ($rp->realisationTaches as $rt) {
            $code = optional($rt->etatRealisationTache?->workflowTache)->code;
            if ($code && $code !== 'TODO') {
                $dt = $rt->dateDebut ?? $rt->updated_at ?? $rt->created_at;
                if ($dt) {
                    $c = \Carbon\Carbon::parse($dt);
                    if (is_null($dateFallback) || $c->lt($dateFallback)) {
                        $dateFallback = $c;
                    }
                }
            }
        }

        return $dateFallback;
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
            'realisationProjets.realisationTaches.tache',
            'realisationProjets.realisationTaches.etatRealisationTache.workflowTache'
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

        $isVisionGlobale = empty($filters['groupe_id']) 
            && empty($filters['groupe_ids']) 
            && empty($filters['projet_id']) 
            && empty($filters['formateur_id']) 
            && empty($filters['filiere_id']);

        if ($isVisionGlobale) {
            $query->take(10);
        }

        $affectations = $query->latest('date_debut')->get();

        $apprenants = [];
        $delaisPresents = [];
        $tranches = [
            'eclair' => 0,   // < 60 min en séance
            'optimal' => 0,  // 60 - 120 min en séance
            'tardif' => 0,   // > 120 min en séance
            'absence' => 0,  // Démarrage avec absence (> fin séance)
            'critique' => 0, // Non amorcé
        ];
        $benchmarkSessions = [];

        foreach ($affectations as $aff) {
            $delaisSessionPresents = [];
            $totalSession = 0;
            $nbPresentsSession = 0;
            $nbAbsenceSession = 0;
            $dateLancement = $aff->cache_date_lancement ?? $aff->date_debut;
            $dateFinSeance = $this->parametrePedagogique()->determinerFinSeanceLancement($dateLancement, $aff->projet_id ?? null);

            foreach ($aff->realisationProjets as $rp) {
                $apprenant = $rp->apprenant;
                if (!$apprenant) continue;

                $totalSession++;
                $minutes = $realisationProjetService->calculerTempsAmorcageMinutes($rp);
                $dateSoumission = $rp->cache_date_premiere_soumission;
                $premiereTache = $rp->realisationTaches->sortBy('created_at')->first()?->tache?->titre;
                $dateEnCours = $this->determinerDatePremiereTacheEnCours($rp);

                $isAmorce = !is_null($minutes);

                if ($isAmorce) {
                    if ($dateEnCours && $dateEnCours->lte($dateFinSeance)) {
                        // Présent en séance de lancement
                        $delaisPresents[] = $minutes;
                        $delaisSessionPresents[] = $minutes;
                        $nbPresentsSession++;

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
                        } else {
                            $tranches['tardif']++;
                            $statutCode = 'tardif';
                            $statutLabel = 'Démarrage tardif en séance';
                            $statutBadge = 'bg-amber-50 text-amber-800 border-amber-200';
                        }
                    } else {
                        // Démarrage avec absence (exclu de la moyenne)
                        $tranches['absence']++;
                        $nbAbsenceSession++;
                        $statutCode = 'demarrage_avec_absence';
                        $statutLabel = 'Démarrage avec absence';
                        $statutBadge = 'bg-purple-50 text-purple-700 border-purple-200';
                    }
                } else {
                    $tranches['critique']++;
                    $statutCode = 'non_amorce';
                    $statutLabel = 'Non amorcé';
                    $statutBadge = 'bg-rose-50 text-rose-700 border-rose-200 animate-pulse';
                }

                $retardPostSeanceMinutes = ($dateEnCours && $dateFinSeance && $dateEnCours->gt($dateFinSeance))
                    ? abs((int) $dateEnCours->diffInMinutes($dateFinSeance))
                    : null;

                $apprenants[] = [
                    'id' => $apprenant->id,
                    'user_id' => $apprenant->user_id,
                    'nom_complet' => ($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? 'Stagiaire'),
                    'prenom' => $apprenant->prenom ?? '',
                    'nom' => $apprenant->nom ?? 'Stagiaire',
                    'matricule' => $apprenant->matricule ?? '-',
                    'email' => $apprenant->user->email ?? null,
                    'avatar' => $apprenant->profile_image ?? null,
                    'groupe_code' => $aff->groupe->code ?? ('Groupe ' . $aff->groupe_id),
                    'projet_titre' => $aff->projet->titre ?? 'Projet',
                    'date_lancement' => $dateLancement ? Carbon::parse($dateLancement)->format('d/m/Y H:i') : null,
                    'date_fin_seance' => $dateFinSeance ? $dateFinSeance->format('d/m/Y H:i') : null,
                    'date_en_cours' => $dateEnCours ? $dateEnCours->format('d/m/Y H:i') : null,
                    'date_premiere_soumission' => $dateSoumission ? Carbon::parse($dateSoumission)->format('d/m/Y H:i') : null,
                    'delai_minutes' => $minutes,
                    'delai_formate' => $this->formaterDureeMinutes($minutes),
                    'retard_post_seance_minutes' => $retardPostSeanceMinutes,
                    'retard_post_seance_formate' => $this->formaterDureeMinutes($retardPostSeanceMinutes),
                    'statut_code' => $statutCode,
                    'statut_label' => $statutLabel,
                    'statut_badge' => $statutBadge,
                    'premiere_tache' => $premiereTache ?? 'Tâche initiale',
                ];
            }

            $moyenSession = count($delaisSessionPresents) > 0 ? (int) round(array_sum($delaisSessionPresents) / count($delaisSessionPresents)) : null;

            $benchmarkSessions[] = [
                'affectation_id' => $aff->id,
                'session_titre' => $aff->projet->titre ?? 'Session',
                'groupe_id' => $aff->groupe_id,
                'groupe_code' => $aff->groupe->code ?? ('Groupe ' . $aff->groupe_id),
                'groupe_nom' => $aff->groupe->nom ?? ($aff->groupe->code ?? 'Promotion'),
                'filiere_code' => $aff->groupe->filiere->code ?? '-',
                'date_debut' => $aff->date_debut ? Carbon::parse($aff->date_debut)->format('d/m/Y') : null,
                'total_apprenants' => $totalSession,
                'nb_amorces' => $nbPresentsSession,
                'nb_demarrage_avec_absence' => $nbAbsenceSession,
                'taux_amorce' => $totalSession > 0 ? round((($nbPresentsSession + $nbAbsenceSession) / $totalSession) * 100, 1) : 0,
                'delai_moyen_minutes' => $moyenSession,
                'delai_moyen_formate' => $this->formaterDureeMinutes($moyenSession),
            ];
        }

        $totalApprenants = count($apprenants);
        $nbPresentsAmorces = count($delaisPresents);
        $nbDemarragesAvecAbsence = $tranches['absence'];
        $nbNonAmorces = $tranches['critique'];
        $tauxAmorce = $totalApprenants > 0 ? round((($nbPresentsAmorces + $nbDemarragesAvecAbsence) / $totalApprenants) * 100, 1) : 0;
        $tauxPresence = $totalApprenants > 0 ? round(($nbPresentsAmorces / $totalApprenants) * 100, 1) : 0;

        $delaiMoyenMinutes = $nbPresentsAmorces > 0 ? (int) round(array_sum($delaisPresents) / $nbPresentsAmorces) : null;
        $delaiMinMinutes = $nbPresentsAmorces > 0 ? min($delaisPresents) : null;
        $delaiMaxMinutes = $nbPresentsAmorces > 0 ? max($delaisPresents) : null;

        // Calcul de la médiane sur les présents
        $delaiMedianMinutes = null;
        if ($nbPresentsAmorces > 0) {
            $delaisTries = $delaisPresents;
            sort($delaisTries);
            $middle = (int) floor($nbPresentsAmorces / 2);
            $delaiMedianMinutes = ($nbPresentsAmorces % 2 === 0) 
                ? (int) round(($delaisTries[$middle - 1] + $delaisTries[$middle]) / 2) 
                : $delaisTries[$middle];
        }

        // Apprenant le plus rapide parmi les présents
        $apprenantRapide = null;
        if ($delaiMinMinutes !== null) {
            foreach ($apprenants as $appr) {
                if ($appr['delai_minutes'] === $delaiMinMinutes && $appr['statut_code'] !== 'demarrage_avec_absence') {
                    $apprenantRapide = $appr;
                    break;
                }
            }
        }

        $benchmark = $benchmarkSessions;

        // Recommandations pédagogiques contextuelles
        $recommandations = [];
        if ($tauxPresence < 65) {
            $recommandations[] = [
                'type' => 'alerte',
                'titre' => 'Forte proportion de démarrages hors séance / absences',
                'message' => 'Moins de 65% des apprenants ont mis en cours leur première tâche pendant la séance de lancement officielle. Il est conseillé de vérifier les présences et de cadrer l\'obligation de prise en main immédiate du sujet dès le lancement.',
                'icone' => 'fa-triangle-exclamation',
                'classe' => 'bg-rose-50 border-rose-200 text-rose-900',
            ];
        } elseif ($tauxPresence < 85) {
            $recommandations[] = [
                'type' => 'vigilance',
                'titre' => 'Accompagnement individualisé des démarrages tardifs',
                'message' => 'Des retards d\'amorçage ont été constatés en séance ou post-séance. Une relance individuelle ou un binômage d\'amorce permettrait de résorber la latence sans ralentir le reste du groupe.',
                'icone' => 'fa-bell',
                'classe' => 'bg-amber-50 border-amber-200 text-amber-900',
            ];
        } else {
            $recommandations[] = [
                'type' => 'succes',
                'titre' => 'Excellente assiduité & dynamique d\'amorce en séance',
                'message' => 'Plus de 85% des apprenants ont amorcé leur travail pendant la séance de lancement officielle. La phase de cadrage initiale est assimilée et le groupe est entré en autonomie de production.',
                'icone' => 'fa-circle-check',
                'classe' => 'bg-emerald-50 border-emerald-200 text-emerald-900',
            ];
        }

        return [
            'total_apprenants' => $totalApprenants,
            'nb_amorces' => $nbPresentsAmorces,
            'nb_demarrages_avec_absence' => $nbDemarragesAvecAbsence,
            'nb_non_amorces' => $nbNonAmorces,
            'taux_amorce' => $tauxAmorce,
            'taux_presence' => $tauxPresence,
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
            'creneaux_seances' => $this->parametrePedagogique()->getCreneauxSeancesFormation(),
        ];
    }
}

