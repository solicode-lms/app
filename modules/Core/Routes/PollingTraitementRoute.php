<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\PollingTraitementController;


Route::get('/admin/traitement/status/{token}', [PollingTraitementController::class, 'status']);