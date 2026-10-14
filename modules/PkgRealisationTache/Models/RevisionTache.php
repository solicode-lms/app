<?php

namespace Modules\PkgRealisationTache\Models;

use Modules\PkgRealisationTache\Models\Base\BaseRevisionTache;
use Modules\PkgRealisationTache\Services\RevisionTacheService;

class RevisionTache extends BaseRevisionTache
{
    protected $casts = [
        'tentative' => 'integer',
        'date_demande' => 'datetime',
        'date_soumission' => 'datetime',
        'date_validation' => 'datetime',
        'est_valide' => 'boolean',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::saved(function (RevisionTache $rt) {
            if ($rt->realisation_tache_id) {
                app(RevisionTacheService::class)->synchroniserCacheRevisionDepuisRevisionTaches($rt->realisation_tache_id);
            }
        });

        static::deleted(function (RevisionTache $rt) {
            if ($rt->realisation_tache_id) {
                app(RevisionTacheService::class)->synchroniserCacheRevisionDepuisRevisionTaches($rt->realisation_tache_id);
            }
        });
    }
}
