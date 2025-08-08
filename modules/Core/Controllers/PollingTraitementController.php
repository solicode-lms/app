<?php

namespace Modules\Core\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Modules\Core\Controllers\Base\AdminController;

class PollingTraitementController extends AdminController
{
 
    /**
     * @DynamicPermissionIgnore
     */
    public function status($token)
    {
        $status = Cache::get("traitement.$token", 'unknown');
        return response()->json(['status' => $status]);
    }

}
