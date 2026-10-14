<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\EtatRealisationTache;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgTableauBord\Services\ParametrePedagogiqueService;

trait DashboardIndicateur05Trait
{
    /**
     * Indicateur 05 — Suivi de la file d'attente Live Coding pour l'Apprenant.
     */
    public function getIndicateurFileAttenteApprenant(?RealisationProjet $realisationProjet, ?User $user = null): array
    {
        $paramService = app(ParametrePedagogiqueService::class);
        $dureeSolo = $paramService->getFileDureePassageSoloMinutes();

        if (!$realisationProjet) {
            return [
                'est_en_file' => false,
                'statut' => 'inactif',
                'rang' => null,
                'temps_attente_minutes' => 0,
                'temps_attente_formate' => '0 min',
                'temps_estime_restant_minutes' => 0,
                'heure_passage_estimee' => null,
                'tache_titre' => null,
                'badge_label' => 'Aucune attente',
                'badge_class' => 'bg-slate-100 text-slate-600',
            ];
        }

        $tacheLive = RealisationTache::where('realisation_projet_id', $realisationProjet->id)
            ->whereHas('tache.phaseEvaluation', fn($q) => $q->where('code', 'N2'))
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->whereIn('code', ['READY_FOR_LIVE_CODING', 'IN_LIVE_CODING']);
            })
            ->with(['tache.phaseEvaluation', 'etatRealisationTache.workflowTache'])
            ->first();

        if (!$tacheLive) {
            return [
                'est_en_file' => false,
                'statut' => 'inactif',
                'rang' => null,
                'temps_attente_minutes' => 0,
                'temps_attente_formate' => '0 min',
                'temps_estime_restant_minutes' => 0,
                'heure_passage_estimee' => null,
                'tache_titre' => null,
                'badge_label' => 'Aucune attente',
                'badge_class' => 'bg-slate-100 text-slate-600',
            ];
        }

        $codeWorkflow = optional($tacheLive->etatRealisationTache?->workflowTache)->code;
        $dateEntree = $tacheLive->cache_date_entree_file ?? $tacheLive->updated_at ?? now();
        $tempsEcouleMinutes = max(0, (int) Carbon::parse($dateEntree)->diffInMinutes(now()));

        if ($codeWorkflow === 'IN_LIVE_CODING') {
            $delai = $tacheLive->cache_delai_attente_minutes ?? $tempsEcouleMinutes;
            return [
                'est_en_file' => true,
                'statut' => 'en_live_coding',
                'rang' => 1,
                'temps_attente_minutes' => $delai,
                'temps_attente_formate' => ParametrePedagogiqueService::formatDureeMinutes($delai),
                'temps_estime_restant_minutes' => 0,
                'heure_passage_estimee' => 'En cours immédiat',
                'tache_titre' => $tacheLive->tache->titre ?? 'Prototype Niveau 2',
                'badge_label' => 'En Live Coding Actif',
                'badge_class' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            ];
        }

        // Cas READY_FOR_LIVE_CODING : calcul du rang FIFO dans l'affectation projet
        $affectationProjetId = $realisationProjet->affectation_projet_id;
        $hasCacheCol = Schema::hasColumn('realisation_taches', 'cache_date_entree_file');

        $queryRangs = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjetId))
            ->whereHas('tache.phaseEvaluation', fn($q) => $q->where('code', 'N2'))
            ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->where('code', 'READY_FOR_LIVE_CODING'));

        if ($hasCacheCol && $tacheLive->cache_date_entree_file) {
            $queryRangs->where(function ($q) use ($tacheLive) {
                $q->where('cache_date_entree_file', '<', $tacheLive->cache_date_entree_file)
                  ->orWhere(function ($sub) use ($tacheLive) {
                      $sub->where('cache_date_entree_file', '=', $tacheLive->cache_date_entree_file)
                          ->where('id', '<', $tacheLive->id);
                  });
            });
        } else {
            $queryRangs->where('id', '<', $tacheLive->id);
        }

        $rang = $queryRangs->count() + 1;
        $tempsEstimeRestantMinutes = ($rang - 1) * $dureeSolo;
        $heurePassageEstimee = now()->addMinutes($tempsEstimeRestantMinutes)->format('H\hi');

        return [
            'est_en_file' => true,
            'statut' => 'en_attente',
            'rang' => $rang,
            'rang_formate' => 'Rang #' . $rang,
            'temps_attente_minutes' => $tempsEcouleMinutes,
            'temps_attente_formate' => ParametrePedagogiqueService::formatDureeMinutes($tempsEcouleMinutes),
            'temps_estime_restant_minutes' => $tempsEstimeRestantMinutes,
            'heure_passage_estimee' => $heurePassageEstimee,
            'tache_titre' => $tacheLive->tache->titre ?? 'Prototype Niveau 2',
            'badge_label' => 'En file #' . $rang,
            'badge_class' => 'bg-sky-100 text-sky-800 border-sky-200',
        ];
    }

    /**
     * Indicateur 05 — Console opérationnelle FIFO & métriques de file pour le Formateur.
     */
    public function getIndicateurFileAttenteFormateur(?AffectationProjet $affectationProjet): array
    {
        if (!$affectationProjet) {
            return [
                'delai_moyen_minutes' => 0,
                'delai_moyen_formate' => '--',
                'total_en_attente' => 0,
                'total_file' => 0,
                'en_cours' => null,
                'file_active' => [],
                'zone' => 'optimal',
                'badge_label' => 'File fluide',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            ];
        }

        $paramService = app(ParametrePedagogiqueService::class);
        $seuilFluide = $paramService->getFileSeuilFluideMinutes();
        $seuilVigilance = $paramService->getFileSeuilVigilanceMinutes();
        $seuilSaturationCount = $paramService->getFileSeuilSaturationCount();
        $dureeSolo = $paramService->getFileDureePassageSoloMinutes();

        $hasCacheCol = Schema::hasColumn('realisation_taches', 'cache_date_entree_file');

        $query = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjet->id))
            ->whereHas('tache.phaseEvaluation', fn($q) => $q->where('code', 'N2'))
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->whereIn('code', ['READY_FOR_LIVE_CODING', 'IN_LIVE_CODING']);
            })
            ->with(['realisationProjet.apprenant.user', 'tache.phaseEvaluation', 'etatRealisationTache.workflowTache']);

        if ($hasCacheCol) {
            $query->orderByRaw("CASE WHEN cache_date_entree_file IS NOT NULL THEN cache_date_entree_file ELSE updated_at END ASC");
        } else {
            $query->orderBy('updated_at', 'asc');
        }
        $query->orderBy('id', 'asc');

        $taches = $query->get();

        $enCoursObj = $taches->first(fn($t) => optional($t->etatRealisationTache?->workflowTache)->code === 'IN_LIVE_CODING');
        $enAttenteCollection = $taches->filter(fn($t) => optional($t->etatRealisationTache?->workflowTache)->code === 'READY_FOR_LIVE_CODING')->values();

        $enCours = null;
        if ($enCoursObj) {
            $apprenant = $enCoursObj->realisationProjet?->apprenant;
            $nomComplet = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? ''));
            $dateDebut = $enCoursObj->cache_date_debut_live_coding ?? $enCoursObj->updated_at ?? now();
            $dureeEnCoursMin = max(0, (int) Carbon::parse($dateDebut)->diffInMinutes(now()));

            $enCours = [
                'id' => $enCoursObj->id,
                'apprenant_id' => $apprenant?->id,
                'apprenant_nom' => $nomComplet ?: 'Apprenant',
                'apprenant_prenom' => $apprenant->prenom ?? '',
                'apprenant_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet ?: 'A') . '&background=c7d2fe&color=3730a3',
                'tache_id' => $enCoursObj->tache_id,
                'tache_titre' => $enCoursObj->tache->titre ?? 'Prototype Niveau 2',
                'nombre_tentatives' => $enCoursObj->cache_nombre_revisions ?? 0,
                'date_debut' => $dateDebut ? Carbon::parse($dateDebut)->format('H\hi') : null,
                'duree_en_cours_minutes' => $dureeEnCoursMin,
                'duree_en_cours_formate' => ParametrePedagogiqueService::formatDureeMinutes($dureeEnCoursMin),
                'session_mutualisee_id' => $enCoursObj->cache_session_live_coding_id ?? null,
                'statut_label' => 'En Live Coding Actif',
            ];
        }

        $fileActive = [];
        $totalAttenteCumul = 0;

        foreach ($enAttenteCollection as $index => $t) {
            $apprenant = $t->realisationProjet?->apprenant;
            $nomComplet = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? ''));
            $dateEntree = $t->cache_date_entree_file ?? $t->updated_at ?? now();
            $minutesAttente = max(0, (int) Carbon::parse($dateEntree)->diffInMinutes(now()));
            $totalAttenteCumul += $minutesAttente;

            $rang = $index + ($enCours ? 2 : 1);
            $estimeMinutes = ($rang - 1) * $dureeSolo;

            $fileActive[] = [
                'id' => $t->id,
                'apprenant_id' => $apprenant?->id,
                'apprenant_nom' => $nomComplet ?: 'Apprenant',
                'apprenant_prenom' => $apprenant->prenom ?? '',
                'apprenant_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet ?: 'A') . '&background=fed7aa&color=c2410c',
                'tache_id' => $t->tache_id,
                'tache_titre' => $t->tache->titre ?? 'Prototype Niveau 2',
                'nombre_tentatives' => $t->cache_nombre_revisions ?? 0,
                'rang' => $rang,
                'minutes_attente' => $minutesAttente,
                'temps_attente_formate' => ParametrePedagogiqueService::formatDureeMinutes($minutesAttente),
                'temps_estime_restant_minutes' => $estimeMinutes,
                'temps_estime_formate' => ParametrePedagogiqueService::formatDureeMinutes($estimeMinutes),
                'heure_passage_estimee' => now()->addMinutes($estimeMinutes)->format('H\hi'),
                'statut_label' => $index === 0 ? 'Suivant' : 'En attente',
                'statut_badge' => $minutesAttente > $seuilVigilance 
                    ? 'bg-rose-100 text-rose-800 border-rose-200' 
                    : ($minutesAttente > $seuilFluide ? 'bg-amber-100 text-amber-800 border-amber-200' : 'bg-sky-100 text-sky-800 border-sky-200'),
            ];
        }

        // Calcul du délai moyen de la séance (passages terminés + apprenants actuellement en file)
        $delaisTermines = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjet->id))
            ->whereHas('tache.phaseEvaluation', fn($q) => $q->where('code', 'N2'))
            ->whereNotNull('cache_delai_attente_minutes')
            ->pluck('cache_delai_attente_minutes');

        $delaisFileActive = collect($fileActive)->pluck('minutes_attente');
        $tousDelais = $delaisTermines->concat($delaisFileActive);

        $delaiMoyen = $tousDelais->isNotEmpty() ? round($tousDelais->avg()) : 0;

        $totalEnAttente = count($fileActive);

        // Seuils didactiques
        if ($totalEnAttente >= $seuilSaturationCount || $delaiMoyen > $seuilVigilance) {
            $zone = 'alerte';
            $badgeLabel = 'Saturation critique';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        } elseif ($delaiMoyen > $seuilFluide || $totalEnAttente >= 3) {
            $zone = 'vigilance';
            $badgeLabel = 'Charge active';
            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
        } else {
            $zone = 'optimal';
            $badgeLabel = 'File fluide';
            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
        }

        return [
            'affectation_id' => $affectationProjet->id,
            'projet_id' => $affectationProjet->projet_id,
            'projet_titre' => $affectationProjet->projet->titre ?? 'Session',
            'groupe_id' => $affectationProjet->groupe_id,
            'groupe_nom' => $affectationProjet->groupe->nom ?? 'Groupe',
            'delai_moyen_minutes' => $delaiMoyen,
            'delai_moyen_formate' => $delaiMoyen > 0 ? ParametrePedagogiqueService::formatDureeMinutes($delaiMoyen) : '< 10 min',
            'total_en_attente' => $totalEnAttente,
            'total_file' => $totalEnAttente + ($enCours ? 1 : 0),
            'en_cours' => $enCours,
            'file_active' => $fileActive,
            'zone' => $zone,
            'badge_label' => $badgeLabel,
            'badge_class' => $badgeClass,
        ];
    }

    /**
     * Indicateur 05 — Gouvernance macroscopique et benchmark Responsable.
     */
    public function getIndicateurFileAttenteResponsable(array $groupeIds = [], ?int $projetId = null): array
    {
        $queryAff = AffectationProjet::with(['groupe', 'projet']);

        if (!empty($groupeIds)) {
            $queryAff->whereIn('groupe_id', $groupeIds);
        }
        if ($projetId) {
            $queryAff->where('projet_id', $projetId);
        }

        $affectations = $queryAff->get();

        $benchmark = [];
        $totalDelais = 0;
        $nbAffAvecDonnees = 0;

        foreach ($affectations as $aff) {
            $kpi = $this->getIndicateurFileAttenteFormateur($aff);
            if ($kpi['delai_moyen_minutes'] > 0 || $kpi['total_en_attente'] > 0) {
                $totalDelais += $kpi['delai_moyen_minutes'];
                $nbAffAvecDonnees++;
            }

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? '',
                'projet_titre' => $aff->projet->titre ?? '',
                'delai_moyen_minutes' => $kpi['delai_moyen_minutes'],
                'delai_moyen_formate' => $kpi['delai_moyen_formate'],
                'total_en_attente' => $kpi['total_en_attente'],
                'zone' => $kpi['zone'],
                'badge_label' => $kpi['badge_label'],
                'badge_class' => $kpi['badge_class'],
            ];
        }

        $delaiMoyenGlobal = $nbAffAvecDonnees > 0 ? round($totalDelais / $nbAffAvecDonnees) : 0;

        return [
            'delai_moyen_global_minutes' => $delaiMoyenGlobal,
            'delai_moyen_global_formate' => $delaiMoyenGlobal > 0 ? ParametrePedagogiqueService::formatDureeMinutes($delaiMoyenGlobal) : '--',
            'benchmark' => $benchmark,
        ];
    }

    /**
     * Indicateur 05 — Page d'analyse détaillée & Console FIFO complète.
     */
    public function getIndicateurFileAttenteDetail(array $filters = []): array
    {
        $paramService = app(ParametrePedagogiqueService::class);
        $dureeSolo = $paramService->getFileDureePassageSoloMinutes();
        $dureeGroupe = $paramService->getFileDureePassageGroupeMinutes();
        $seuilFluide = $paramService->getFileSeuilFluideMinutes();
        $seuilVigilance = $paramService->getFileSeuilVigilanceMinutes();
        $seuilSaturationCount = $paramService->getFileSeuilSaturationCount();

        // 1. Déterminer les affectations dans le scope
        $queryAff = AffectationProjet::with(['projet.formateur', 'groupe.formateurs']);

        if (!empty($filters['projet_id'])) {
            $queryAff->where('projet_id', $filters['projet_id']);
        }
        if (!empty($filters['groupe_ids'])) {
            $queryAff->whereIn('groupe_id', $filters['groupe_ids']);
        }
        if (!empty($filters['filiere_id'])) {
            $queryAff->whereHas('groupe', fn($q) => $q->where('filiere_id', $filters['filiere_id']));
        }
        if (!empty($filters['formateur_id'])) {
            $formateurId = $filters['formateur_id'];
            $queryAff->where(function ($q) use ($formateurId) {
                $q->whereHas('projet', fn($sub) => $sub->where('formateur_id', $formateurId))
                  ->orWhereHas('groupe.formateurs', fn($sub) => $sub->where('formateurs.id', $formateurId));
            });
        }

        $affectations = $queryAff->get();

        // Si aucune affectation ne correspond, repli sur l'affectation active avec live coding ou fallback
        if ($affectations->isEmpty()) {
            $affectations = AffectationProjet::with(['projet.formateur', 'groupe.formateurs'])->get();
        }

        // 2. Sélectionner l'affectation active pour la console FIFO
        $selectedAff = null;
        if (!empty($filters['projet_id'])) {
            $selectedAff = $affectations->firstWhere('projet_id', $filters['projet_id']);
        }

        if (!$selectedAff) {
            // Priorité 1 : Chercher d'abord une affectation avec du IN_LIVE_CODING actif sur N2
            foreach ($affectations as $aff) {
                $hasInLive = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $aff->id))
                    ->whereHas('tache.phaseEvaluation', fn($q) => $q->where('code', 'N2'))
                    ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->where('code', 'IN_LIVE_CODING'))
                    ->exists();
                if ($hasInLive) {
                    $selectedAff = $aff;
                    break;
                }
            }
        }

        if (!$selectedAff) {
            // Priorité 2 : Chercher une affectation avec du READY_FOR_LIVE_CODING sur N2
            foreach ($affectations as $aff) {
                $hasActive = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $aff->id))
                    ->whereHas('tache.phaseEvaluation', fn($q) => $q->where('code', 'N2'))
                    ->whereHas('etatRealisationTache.workflowTache', fn($q) => $q->where('code', 'READY_FOR_LIVE_CODING'))
                    ->exists();
                if ($hasActive) {
                    $selectedAff = $aff;
                    break;
                }
            }
        }

        if (!$selectedAff && $affectations->isNotEmpty()) {
            $selectedAff = $affectations->first();
        }

        // 3. Calculer les données de l'affectation active
        $kpiActive = $this->getIndicateurFileAttenteFormateur($selectedAff);

        // 4. Soutenances récentes terminées dans cette affectation (tâches N2)
        $soutenancesRecentes = [];
        if ($selectedAff) {
            $historiqueTaches = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $selectedAff->id))
                ->whereHas('tache.phaseEvaluation', fn($q) => $q->where('code', 'N2'))
                ->where(function ($q) {
                    $q->whereNotNull('cache_delai_attente_minutes')
                      ->orWhereNotNull('cache_date_debut_live_coding');
                })
                ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                    $q->whereIn('code', ['APPROVED', 'REVISION_NECESSAIRE', 'TO_APPROVE', 'NOT_VALIDATED']);
                })
                ->with(['realisationProjet.apprenant', 'tache', 'etatRealisationTache.workflowTache'])
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            foreach ($historiqueTaches as $ht) {
                $appr = $ht->realisationProjet?->apprenant;
                $nomAppr = trim(($appr->prenom ?? '') . ' ' . ($appr->nom ?? ''));
                $codeWf = optional($ht->etatRealisationTache?->workflowTache)->code;

                $decisionLabel = $codeWf === 'APPROVED' ? 'Validé' : ($codeWf === 'REVISION_NECESSAIRE' ? 'Révision demandée' : 'Évalué');
                $decisionBadge = $codeWf === 'APPROVED' 
                    ? 'bg-emerald-100 text-emerald-800 border-emerald-200' 
                    : 'bg-rose-100 text-rose-800 border-rose-200';

                $delaiMin = $ht->cache_delai_attente_minutes ?? 0;

                $soutenancesRecentes[] = [
                    'id' => $ht->id,
                    'apprenant_nom' => $nomAppr ?: 'Apprenant',
                    'apprenant_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nomAppr ?: 'A') . '&background=e2e8f0&color=334155',
                    'tache_titre' => $ht->tache->titre ?? 'Prototype',
                    'delai_minutes' => $delaiMin,
                    'delai_formate' => ParametrePedagogiqueService::formatDureeMinutes($delaiMin),
                    'date_passage' => $ht->cache_date_debut_live_coding ? Carbon::parse($ht->cache_date_debut_live_coding)->format('d/m H\hi') : $ht->updated_at->format('d/m H\hi'),
                    'decision' => $decisionLabel,
                    'decision_badge' => $decisionBadge,
                    'note' => $ht->note,
                ];
            }
        }

        // 5. Benchmark multi-sessions
        $benchmark = [];
        $totalDelaisBench = 0;
        $totalEnAttenteBench = 0;
        $totalEnCoursBench = 0;
        $nbAffAvecStats = 0;

        foreach ($affectations as $aff) {
            $affKpi = $this->getIndicateurFileAttenteFormateur($aff);
            if ($affKpi['delai_moyen_minutes'] > 0 || $affKpi['total_en_attente'] > 0) {
                $totalDelaisBench += $affKpi['delai_moyen_minutes'];
                $nbAffAvecStats++;
            }
            $totalEnAttenteBench += $affKpi['total_en_attente'];
            $totalEnCoursBench += ($affKpi['en_cours'] ? 1 : 0);

            $formateurNom = $aff->projet?->formateur 
                ? ($aff->projet->formateur->prenom . ' ' . $aff->projet->formateur->nom) 
                : ($aff->groupe?->formateurs?->first() ? ($aff->groupe->formateurs->first()->prenom . ' ' . $aff->groupe->formateurs->first()->nom) : 'Non assigné');

            $benchmark[] = [
                'affectation_id' => $aff->id,
                'is_active' => $selectedAff && $aff->id === $selectedAff->id,
                'projet_id' => $aff->projet_id,
                'projet_titre' => $aff->projet->titre ?? 'Session',
                'groupe_id' => $aff->groupe_id,
                'groupe_code' => $aff->groupe->code ?? 'Groupe',
                'groupe_nom' => $aff->groupe->nom ?? '',
                'formateur_nom' => $formateurNom,
                'total_en_attente' => $affKpi['total_en_attente'],
                'en_cours_nom' => $affKpi['en_cours']['apprenant_nom'] ?? null,
                'delai_moyen_minutes' => $affKpi['delai_moyen_minutes'],
                'delai_moyen_formate' => $affKpi['delai_moyen_formate'],
                'zone' => $affKpi['zone'],
                'badge_label' => $affKpi['badge_label'],
                'badge_class' => $affKpi['badge_class'],
            ];
        }

        // Projections temporelles pour l'affectation active
        $totalEnAttente = $kpiActive['total_en_attente'];
        $tempsEstimeSoloMin = $totalEnAttente * $dureeSolo;
        $tempsEstimeGroupeMin = (int) (ceil($totalEnAttente / 4) * $dureeGroupe);
        $heureFinEstimeeSolo = now()->addMinutes($tempsEstimeSoloMin)->format('H\hi');
        $heureFinEstimeeGroupe = now()->addMinutes($tempsEstimeGroupeMin)->format('H\hi');

        $cadenceRecommandee = ($totalEnAttente >= $seuilSaturationCount || $kpiActive['delai_moyen_minutes'] > $seuilVigilance)
            ? 'Session Mutualisée Discord 4x4'
            : 'Passage Solo 1-à-1';

        return [
            'affectation_active' => $selectedAff,
            'affectation_id' => $selectedAff?->id,
            'projet_id' => $selectedAff?->projet_id,
            'projet_titre' => $selectedAff?->projet->titre ?? 'Session active',
            'groupe_nom' => $selectedAff?->groupe->nom ?? 'Groupe',
            // Métriques clés
            'delai_moyen_minutes' => $kpiActive['delai_moyen_minutes'],
            'delai_moyen_formate' => $kpiActive['delai_moyen_formate'],
            'statut' => $kpiActive['badge_label'],
            'statut_badge' => $kpiActive['badge_class'],
            'zone' => $kpiActive['zone'],
            'total_en_attente' => $totalEnAttente,
            'total_en_cours' => $kpiActive['en_cours'] ? 1 : 0,
            'total_file' => $kpiActive['total_file'],
            'en_cours' => $kpiActive['en_cours'],
            'file_active' => $kpiActive['file_active'],
            // Projections
            'temps_total_estime_solo_minutes' => $tempsEstimeSoloMin,
            'temps_total_estime_solo_formate' => ParametrePedagogiqueService::formatDureeMinutes($tempsEstimeSoloMin),
            'temps_total_estime_groupe_minutes' => $tempsEstimeGroupeMin,
            'temps_total_estime_groupe_formate' => ParametrePedagogiqueService::formatDureeMinutes($tempsEstimeGroupeMin),
            'heure_fin_estimee_solo' => $heureFinEstimeeSolo,
            'heure_fin_estimee_groupe' => $heureFinEstimeeGroupe,
            'cadence_recommandee' => $cadenceRecommandee,
            // Paramètres & Seuils
            'seuil_fluide' => $seuilFluide,
            'seuil_vigilance' => $seuilVigilance,
            'seuil_saturation_count' => $seuilSaturationCount,
            'duree_solo' => $dureeSolo,
            'duree_groupe' => $dureeGroupe,
            // Collections
            'soutenances_recentes' => $soutenancesRecentes,
            'benchmark' => $benchmark,
            'total_global_en_attente' => $totalEnAttenteBench,
            'total_global_en_cours' => $totalEnCoursBench,
            'delai_moyen_global' => $nbAffAvecStats > 0 ? round($totalDelaisBench / $nbAffAvecStats) : 0,
        ];
    }

    /**
     * Action opérationnelle : Appel d'un apprenant en soutenance Live Coding individuelle.
     */
    public function demarrerLiveCodingSolo(int $realisationTacheId): array
    {
        $realisationTache = RealisationTache::findOrFail($realisationTacheId);

        $etatInLive = EtatRealisationTache::whereHas('workflowTache', fn($q) => $q->where('code', 'IN_LIVE_CODING'))->first();
        if (!$etatInLive) {
            throw new \RuntimeException("État workflow IN_LIVE_CODING introuvable.");
        }

        $now = now();
        $dateEntree = $realisationTache->cache_date_entree_file ?? $realisationTache->updated_at ?? $now;
        $delaiAttente = max(0, (int) Carbon::parse($dateEntree)->diffInMinutes($now));

        DB::table('realisation_taches')
            ->where('id', $realisationTacheId)
            ->update([
                'etat_realisation_tache_id' => $etatInLive->id,
                'cache_date_debut_live_coding' => $now,
                'cache_delai_attente_minutes' => $delaiAttente,
                'updated_at' => $now,
            ]);

        return [
            'success' => true,
            'message' => 'Apprenant appelé avec succès en Live Coding.',
            'delai_attente_minutes' => $delaiAttente,
        ];
    }

    /**
     * Action opérationnelle : Démarrage d'une session mutualisée (jusqu'à 4 apprenants simultanés).
     */
    public function demarrerLiveCodingGroupe(array $realisationTacheIds): array
    {
        if (empty($realisationTacheIds)) {
            return ['success' => false, 'message' => 'Aucun apprenant sélectionné.'];
        }

        $etatInLive = EtatRealisationTache::whereHas('workflowTache', fn($q) => $q->where('code', 'IN_LIVE_CODING'))->first();
        if (!$etatInLive) {
            throw new \RuntimeException("État workflow IN_LIVE_CODING introuvable.");
        }

        $now = now();
        $sessionId = (int) (microtime(true) * 1000);

        foreach ($realisationTacheIds as $rtId) {
            $rt = RealisationTache::find($rtId);
            if (!$rt) {
                continue;
            }

            $dateEntree = $rt->cache_date_entree_file ?? $rt->updated_at ?? $now;
            $delaiAttente = max(0, (int) Carbon::parse($dateEntree)->diffInMinutes($now));

            DB::table('realisation_taches')
                ->where('id', $rtId)
                ->update([
                    'etat_realisation_tache_id' => $etatInLive->id,
                    'cache_date_debut_live_coding' => $now,
                    'cache_delai_attente_minutes' => $delaiAttente,
                    'cache_session_live_coding_id' => $sessionId,
                    'updated_at' => $now,
                ]);
        }

        return [
            'success' => true,
            'message' => 'Session mutualisée lancée pour ' . count($realisationTacheIds) . ' apprenants.',
            'session_id' => $sessionId,
        ];
    }

    /**
     * Action opérationnelle : Clôture de la soutenance avec validation ou demande de révision.
     */
    public function terminerLiveCoding(int $realisationTacheId, string $decision, ?float $note = null, ?string $remarques = null): array
    {
        $realisationTache = RealisationTache::findOrFail($realisationTacheId);

        if ($decision === 'APPROVED') {
            $etat = EtatRealisationTache::whereHas('workflowTache', fn($q) => $q->where('code', 'APPROVED'))->first();
            $updates = [
                'etat_realisation_tache_id' => $etat->id,
                'updated_at' => now(),
            ];
            if ($note !== null) {
                $updates['note'] = $note;
            }
            if ($remarques !== null) {
                $updates['remarques_formateur'] = $remarques;
            }

            DB::table('realisation_taches')->where('id', $realisationTacheId)->update($updates);

            return ['success' => true, 'message' => 'Prototype validé avec succès.'];
        }

        if ($decision === 'REVISION_NECESSAIRE') {
            $etat = EtatRealisationTache::whereHas('workflowTache', fn($q) => $q->where('code', 'REVISION_NECESSAIRE'))->first();
            $updates = [
                'etat_realisation_tache_id' => $etat->id,
                'cache_nombre_revisions' => DB::raw('COALESCE(cache_nombre_revisions, 0) + 1'),
                'updated_at' => now(),
            ];
            if ($remarques !== null) {
                $updates['remarques_formateur'] = $remarques;
            }

            DB::table('realisation_taches')->where('id', $realisationTacheId)->update($updates);

            return ['success' => true, 'message' => 'Révision demandée. La tâche a été renvoyée à l\'apprenant.'];
        }

        return ['success' => false, 'message' => 'Décision invalide.'];
    }
}
