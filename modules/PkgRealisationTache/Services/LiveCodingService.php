<?php

namespace Modules\PkgRealisationTache\Services;

use Illuminate\Support\Facades\DB;
use Modules\PkgRealisationProjets\Models\RealisationProjet;
use Modules\PkgRealisationTache\Models\LiveCoding;
use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\Base\BaseLiveCodingService;

/**
 * Classe LiveCodingService pour gérer la persistance et la logique métier de LiveCoding.
 */
class LiveCodingService extends BaseLiveCodingService
{
    /**
     * Enregistre automatiquement une tentative de Live Coding lors de l'évaluation formateur.
     *
     * @param RealisationTache $realisationTache
     * @param string $etatWorkflowCode ('APPROVED', 'NOT_VALIDATED', 'EN_REMEDIATION')
     * @return LiveCoding|null
     */
    public function enregistrerTentativeLiveCoding(RealisationTache $realisationTache, string $etatWorkflowCode): ?LiveCoding
    {
        if (!$realisationTache->isPrototype()) {
            return null;
        }

        $nbExistants = LiveCoding::where('realisation_tache_id', $realisationTache->id)->count();
        $tentative = $nbExistants + 1;
        $estValide = ($etatWorkflowCode === 'APPROVED');

        $liveCoding = LiveCoding::create([
            'reference' => 'LC-RT' . $realisationTache->id . '-' . $tentative . '-' . substr(uniqid(), -4),
            'realisation_tache_id' => $realisationTache->id,
            'tentative' => $tentative,
            'est_valide' => $estValide,
            'date_passage' => now(),
            'commentaire' => $realisationTache->remarque_evaluateur ?? null,
        ]);

        $this->synchroniserCacheGatingDepuisLiveCodings($realisationTache);

        return $liveCoding;
    }

    /**
     * Recalcule et synchronise les champs de cache Gating (R_gating) sur realisation_projets
     * à partir des enregistrements réels dans live_codings.
     *
     * @param RealisationTache $realisationTache
     * @return void
     */
    public function synchroniserCacheGatingDepuisLiveCodings(RealisationTache $realisationTache): void
    {
        $projetId = $realisationTache->realisation_projet_id;
        if (!$projetId) {
            return;
        }

        $liveCodings = LiveCoding::whereHas('realisationTache', function ($q) use ($projetId) {
            $q->where('realisation_projet_id', $projetId);
        })->orderBy('date_passage', 'asc')->orderBy('id', 'asc')->get();

        if ($liveCodings->isEmpty()) {
            DB::table('realisation_projets')->where('id', $projetId)->update([
                'cache_nombre_tentatives_prototype' => 0,
                'cache_est_valide_prototype_premier_essai' => null,
                'cache_date_validation_prototype' => null,
                'updated_at' => now(),
            ]);
            return;
        }

        $premiereTentative = $liveCodings->first();
        $tentativeValidee = $liveCodings->firstWhere('est_valide', true);

        DB::table('realisation_projets')->where('id', $projetId)->update([
            'cache_nombre_tentatives_prototype' => $liveCodings->count(),
            'cache_est_valide_prototype_premier_essai' => (bool) $premiereTentative->est_valide,
            'cache_date_validation_prototype' => $tentativeValidee?->date_passage ?? null,
            'updated_at' => now(),
        ]);
    }

    /**
     * Hook exécuté après la création d'une tentative
     */
    public function afterCreateRules($item)
    {
        if ($item && $item->realisationTache) {
            $this->synchroniserCacheGatingDepuisLiveCodings($item->realisationTache);
        }
    }

    /**
     * Hook exécuté après la modification d'une tentative
     */
    public function afterUpdateRules($item, $id)
    {
        if ($item && $item->realisationTache) {
            $this->synchroniserCacheGatingDepuisLiveCodings($item->realisationTache);
        }
    }

    /**
     * Hook exécuté après la suppression d'une tentative
     */
    public function afterDeleteRules($item, $id)
    {
        if ($item && $item->realisationTache) {
            $this->synchroniserCacheGatingDepuisLiveCodings($item->realisationTache);
        }
    }
}
