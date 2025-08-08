<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\PollingTraitementController;

Route::post('/admin/traitement/start', [PollingTraitementController::class, 'start']);
Route::get('/admin/traitement/status/{t
oken}', [PollingTraitementController::class, 'status']);