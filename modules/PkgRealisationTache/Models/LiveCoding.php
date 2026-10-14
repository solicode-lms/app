<?php
namespace Modules\PkgRealisationTache\Models;

use Modules\PkgRealisationTache\Models\Base\BaseLiveCoding;
use Modules\PkgRealisationTache\Services\LiveCodingService;

class LiveCoding extends BaseLiveCoding
{
    protected $casts = [
        'est_valide' => 'boolean',
        'tentative' => 'integer',
        'date_passage' => 'datetime',
    ];

    protected static function booted(): void
    {
        parent::booted();

        static::saved(function (LiveCoding $lc) {
            if ($lc->realisationTache) {
                app(LiveCodingService::class)->synchroniserCacheGatingDepuisLiveCodings($lc->realisationTache);
            }
        });

        static::deleted(function (LiveCoding $lc) {
            if ($lc->realisationTache) {
                app(LiveCodingService::class)->synchroniserCacheGatingDepuisLiveCodings($lc->realisationTache);
            }
        });
    }
}
