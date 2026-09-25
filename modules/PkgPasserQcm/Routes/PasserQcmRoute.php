<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgPasserQcm\Controllers\PasserQcmController;

// Routes spécifiques pour l'interface de passage des QCMs par les apprenants
Route::middleware(['auth'])->group(function () {
    Route::get('passer-qcm/{realisation_qcm_id}', [PasserQcmController::class, 'index'])->name('passerQcm.index');
    Route::post('passer-qcm/{realisation_qcm_id}/start', [PasserQcmController::class, 'start'])->name('passerQcm.start');
    Route::post('passer-qcm/{realisation_qcm_id}/save-incremental', [PasserQcmController::class, 'saveIncremental'])->name('passerQcm.save-incremental');
    Route::post('passer-qcm/{realisation_qcm_id}/submit', [PasserQcmController::class, 'submit'])->name('passerQcm.submit');
});
