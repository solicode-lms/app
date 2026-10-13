<?php

namespace Modules\PkgTableauBord\Services\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\PkgApprenants\Models\Apprenant;
use Modules\PkgAutorisation\Models\User;
use Modules\PkgRealisationProjets\Models\AffectationProjet;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\RealisationTache;

trait DashboardIndicateur05Trait
{
    /**
     * Indicateur 05 — Suivi de la file d'attente Live Coding pour l'Apprenant.
     */
    public function getIndicateurFileAttenteApprenant(?RealisationProjet $realisationProjet, ?User $user = null): array
    {
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
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->whereIn('code', ['READY_FOR_LIVE_CODING', 'IN_LIVE_CODING']);
            })
            ->with(['tache', 'etatRealisationTache.workflowTache'])
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
        $dateEntree = $tacheLive->cache_date_entree_file ?? $tacheLive->created_at ?? now();
        $tempsEcouleMinutes = max(0, (int) Carbon::parse($dateEntree)->diffInMinutes(now()));

        if ($codeWorkflow === 'IN_LIVE_CODING') {
            return [
                'est_en_file' => true,
                'statut' => 'en_live_coding',
                'rang' => 1,
                'temps_attente_minutes' => $tacheLive->cache_delai_attente_minutes ?? $tempsEcouleMinutes,
                'temps_attente_formate' => ($tacheLive->cache_delai_attente_minutes ?? $tempsEcouleMinutes) . ' min',
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
        $tempsEstimeRestantMinutes = ($rang - 1) * 18; // estimation 18 min par passage
        $heurePassageEstimee = now()->addMinutes($tempsEstimeRestantMinutes)->format('H\hi');

        return [
            'est_en_file' => true,
            'statut' => 'en_attente',
            'rang' => $rang,
            'rang_formate' => 'Rang #' . $rang,
            'temps_attente_minutes' => $tempsEcouleMinutes,
            'temps_attente_formate' => $tempsEcouleMinutes . ' minutes',
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
                'en_cours' => null,
                'file_active' => [],
                'zone' => 'optimal',
                'badge_label' => 'File fluide',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            ];
        }

        $hasCacheCol = Schema::hasColumn('realisation_taches', 'cache_date_entree_file');

        $query = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjet->id))
            ->whereHas('etatRealisationTache.workflowTache', function ($q) {
                $q->whereIn('code', ['READY_FOR_LIVE_CODING', 'IN_LIVE_CODING']);
            })
            ->with(['realisationProjet.apprenant.user', 'tache', 'etatRealisationTache.workflowTache']);

        if ($hasCacheCol) {
            $query->orderByRaw("CASE WHEN cache_date_entree_file IS NOT NULL THEN cache_date_entree_file ELSE created_at END ASC");
        } else {
            $query->orderBy('created_at', 'asc');
        }
        $query->orderBy('id', 'asc');

        $taches = $query->get();

        $enCoursObj = $taches->first(fn($t) => optional($t->etatRealisationTache?->workflowTache)->code === 'IN_LIVE_CODING');
        $enAttenteCollection = $taches->filter(fn($t) => optional($t->etatRealisationTache?->workflowTache)->code === 'READY_FOR_LIVE_CODING')->values();

        $enCours = null;
        if ($enCoursObj) {
            $apprenant = $enCoursObj->realisationProjet?->apprenant;
            $nomComplet = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? ''));
            $enCours = [
                'id' => $enCoursObj->id,
                'apprenant_id' => $apprenant?->id,
                'apprenant_nom' => $nomComplet ?: 'Apprenant',
                'apprenant_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet ?: 'A') . '&background=c7d2fe&color=3730a3',
                'tache_titre' => $enCoursObj->tache->titre ?? 'Prototype Niveau 2',
                'nombre_tentatives' => $enCoursObj->cache_nombre_revisions ?? 0,
                'statut_label' => 'En Live Coding Actif',
            ];
        }

        $fileActive = [];
        $totalAttenteCumul = 0;

        foreach ($enAttenteCollection as $index => $t) {
            $apprenant = $t->realisationProjet?->apprenant;
            $nomComplet = trim(($apprenant->prenom ?? '') . ' ' . ($apprenant->nom ?? ''));
            $dateEntree = $t->cache_date_entree_file ?? $t->created_at ?? now();
            $minutesAttente = max(0, (int) Carbon::parse($dateEntree)->diffInMinutes(now()));
            $totalAttenteCumul += $minutesAttente;

            $rang = $index + ($enCours ? 2 : 1);

            $fileActive[] = [
                'id' => $t->id,
                'apprenant_id' => $apprenant?->id,
                'apprenant_nom' => $nomComplet ?: 'Apprenant',
                'apprenant_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nomComplet ?: 'A') . '&background=fed7aa&color=c2410c',
                'tache_titre' => $t->tache->titre ?? 'Prototype Niveau 2',
                'nombre_tentatives' => $t->cache_nombre_revisions ?? 0,
                'rang' => $rang,
                'minutes_attente' => $minutesAttente,
                'temps_attente_formate' => sprintf('%02d:00 min', $minutesAttente),
                'statut_label' => $index === 0 ? 'Suivant' : 'En attente',
                'statut_badge' => $index === 0 ? 'bg-sky-100 text-sky-800 border-sky-200' : 'bg-slate-100 text-slate-700 border-slate-200',
            ];
        }

        // Calcul du délai moyen historique ou séance
        $delaisTermines = RealisationTache::whereHas('realisationProjet', fn($q) => $q->where('affectation_projet_id', $affectationProjet->id))
            ->whereNotNull('cache_delai_attente_minutes')
            ->pluck('cache_delai_attente_minutes');

        if ($delaisTermines->isNotEmpty()) {
            $delaiMoyen = round($delaisTermines->avg());
        } elseif ($enAttenteCollection->isNotEmpty()) {
            $delaiMoyen = round($totalAttenteCumul / max(count($fileActive), 1));
        } else {
            $delaiMoyen = 0;
        }

        $totalEnAttente = count($fileActive);

        // Seuils didactiques : Fluide (<= 30 min), Vigilance (30-60 min), Saturation (> 60 min ou >= 5 en attente)
        if ($totalEnAttente >= 5 || $delaiMoyen > 60) {
            $zone = 'alerte';
            $badgeLabel = 'Saturation critique';
            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
        } elseif ($delaiMoyen > 30 || $totalEnAttente >= 3) {
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
            'delai_moyen_minutes' => $delaiMoyen,
            'delai_moyen_formate' => $delaiMoyen > 0 ? $delaiMoyen . ' min' : '< 10 min',
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
            'delai_moyen_global_formate' => $delaiMoyenGlobal > 0 ? $delaiMoyenGlobal . ' min' : '--',
            'benchmark' => $benchmark,
        ];
    }
}
