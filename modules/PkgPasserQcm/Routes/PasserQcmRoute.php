<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgPasserQcm\Controllers\PasserQcmController;

// Routes spécifiques pour l'interface de passage des QCMs par les apprenants
Route::middleware(['auth'])->group(function () {
    Route::get('passer-qcm/{realisation_qcm_id}', [PasserQcmController::class, 'index'])->name('passerQcm.index');
});
