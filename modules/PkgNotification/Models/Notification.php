<?php


namespace Modules\PkgNotification\Models;
use Modules\PkgNotification\Models\Base\BaseNotification;

class Notification extends BaseNotification
{
    protected $casts = [
        'data' => 'array', // ✅ Auto-décodage JSON
        'sent_at' => 'datetime', // ✅ Bonus pour accéder à sent_at comme Carbon instance
    ];
}
