<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\AffectationQcmProjetController;

// Regroupement identique au fichier natif pour préserver la sécurité et les URLs
Route::middleware('auth')->group(function () {
    Route::prefix('/admin/PkgQcm')->group(function () {
        
        // Nouvelle route personnalisée chargée en PRIORITÉ
        Route::get('affectationQcmProjets/{id}/prompt', [AffectationQcmProjetController::class, 'prompt'])
            ->name('affectationQcmProjets.prompt');
            
        Route::post('affectationQcmProjets/{id}/import-ia', [AffectationQcmProjetController::class, 'importIaProcess'])
            ->name('affectationQcmProjets.importIaProcess');
            
        Route::get('affectationQcmProjets/{id}/questions-count', [AffectationQcmProjetController::class, 'getQuestionsCount'])
            ->name('affectationQcmProjets.getQuestionsCount');
    });
});
