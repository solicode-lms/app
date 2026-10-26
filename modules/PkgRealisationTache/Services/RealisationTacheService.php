<?php

namespace Modules\PkgRealisationTache\Services;

use Modules\PkgRealisationTache\Models\RealisationTache;
use Modules\PkgRealisationTache\Services\Base\BaseRealisationTacheService;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheCrudTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheCreationHookTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheAbsenceTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheRemediationTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheChapitreUaTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheActionsTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheNoteTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheGetterTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheFilterTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheJobTrait;
use Modules\PkgRealisationTache\Services\Traits\RealisationTache\RealisationTacheMassCrudTrait;

/**
 * Classe RealisationTacheService pour gérer la persistance de l'entité RealisationTache.
 *
 * Architecture ultra-modulaire via Traits (< 300 lignes par fichier) :
 * @uses RealisationTacheCrudTrait Hooks de mise à jour (beforeUpdateRules, afterEditRules, afterUpdateRules).
 * @uses RealisationTacheCreationHookTrait Hooks de création (beforeCreateRules, afterCreateRules).
 * @uses RealisationTacheAbsenceTrait Gestion de l'absence, reprises et propagations.
 * @uses RealisationTacheRemediationTrait Synchronisation de la table remediations.
 * @uses RealisationTacheChapitreUaTrait Liaisons chapitres et validations UA.
 * @uses RealisationTacheActionsTrait Actions métier spécifiques et gestion des remarques formateur.
 * @uses RealisationTacheNoteTrait Répartition et calculs mathématiques des notes (N2/N3).
 * @uses RealisationTacheGetterTrait Méthodes de lecture, tris et scopes.
 * @uses RealisationTacheFilterTrait Configuration de la barre de filtres TableUI.
 * @uses RealisationTacheJobTrait Gestion des Jobs asynchrones.
 * @uses RealisationTacheMassCrudTrait Opérations de masse.
 */
class RealisationTacheService extends BaseRealisationTacheService
{
    use RealisationTacheCrudTrait,
        RealisationTacheCreationHookTrait,
        RealisationTacheAbsenceTrait,
        RealisationTacheRemediationTrait,
        RealisationTacheChapitreUaTrait,
        RealisationTacheJobTrait,
        RealisationTacheActionsTrait,
        RealisationTacheNoteTrait,
        RealisationTacheGetterTrait,
        RealisationTacheFilterTrait,
        RealisationTacheMassCrudTrait;

    protected $paginationLimit = 25;
    protected array $index_with_relations = [
        'tache',
        'realisationChapitres',
        'tacheAffectation',
        'tache.livrables',
        'etatRealisationTache',
        'historiqueRealisationTaches',
        'realisationProjet.apprenant',
        'realisationProjet.affectationProjet',
        'tache.livrables.natureLivrable',
        'livrablesRealisations.livrable.taches',
        'realisationProjet.realisationTaches.tache',
    ];

    /**
     * Incrémente le compteur dénormalisé de révisions lors d'un renvoi en correction.
     *
     * @param RealisationTache $realisationTache
     * @return void
     */
    public function incrementerNombreRevisions(RealisationTache $realisationTache): void
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('realisation_taches', 'cache_nombre_revisions')) {
            \Illuminate\Support\Facades\DB::table('realisation_taches')
                ->where('id', $realisationTache->id)
                ->increment('cache_nombre_revisions');
        }
    }

    /**
     * Enregistre l'horodatage d'entrée dans la file d'attente FIFO (Indicateur 05 — T_file).
     */
    public function enregistrerEntreeFileLiveCoding(RealisationTache $realisationTache): void
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('realisation_taches', 'cache_date_entree_file')) {
            \Illuminate\Support\Facades\DB::table('realisation_taches')
                ->where('id', $realisationTache->id)
                ->whereNull('cache_date_entree_file')
                ->update(['cache_date_entree_file' => now()]);
        }
    }

    /**
     * Enregistre le démarrage du Live Coding et calcule le délai d'attente en minutes (Indicateur 05 — T_file).
     */
    public function enregistrerPriseEnChargeLiveCoding(RealisationTache $realisationTache, ?string $sessionId = null): void
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('realisation_taches', 'cache_date_debut_live_coding')) {
            $now = now();
            $dateEntree = $realisationTache->cache_date_entree_file ?? $realisationTache->updated_at ?? $now;
            $delaiMinutes = max(0, (int) \Carbon\Carbon::parse($dateEntree)->diffInMinutes($now));

            $updates = [
                'cache_date_debut_live_coding' => $now,
                'cache_delai_attente_minutes' => $delaiMinutes,
            ];

            if ($sessionId && \Illuminate\Support\Facades\Schema::hasColumn('realisation_taches', 'cache_session_live_coding_id')) {
                $updates['cache_session_live_coding_id'] = $sessionId;
            }

            \Illuminate\Support\Facades\DB::table('realisation_taches')
                ->where('id', $realisationTache->id)
                ->update($updates);
        }
    }
}
