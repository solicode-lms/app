<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgTableauBord\Controllers\ActiviteFormateurController;
use Modules\PkgTableauBord\Controllers\TableauBordApprenantController;
use Modules\PkgTableauBord\Controllers\TableauBordFormateurController;
use Modules\PkgTableauBord\Controllers\TableauBordRechercheController;
use Modules\PkgTableauBord\Controllers\IndicateurDetailController;
use Modules\PkgTableauBord\Controllers\TableauBordResponsableController;

Route::middleware('auth')->group(function () {
    Route::prefix('admin/PkgTableauBord')->name('admin.PkgTableauBord.')->group(function () {
        Route::get('/apprenant', [TableauBordApprenantController::class, 'index'])->name('apprenant');
        Route::get('/formateur', [TableauBordFormateurController::class, 'index'])->name('formateur');
        Route::get('/responsable', [TableauBordResponsableController::class, 'index'])->name('responsable');
        Route::get('/recherche', [TableauBordRechercheController::class, 'index'])->name('recherche');
        Route::post('/telemetrie/activite', [ActiviteFormateurController::class, 'ping'])->name('telemetrie.activite');

        // Détails par indicateur
        Route::prefix('indicateurs')->name('indicateurs.')->group(function () {
            Route::get('/amorcage', [IndicateurDetailController::class, 'amorcage'])->name('amorcage');
            Route::get('/gating', [IndicateurDetailController::class, 'gating'])->name('gating');
            Route::get('/revisions', [IndicateurDetailController::class, 'revisions'])->name('revisions');
            Route::get('/charge-logistique', [IndicateurDetailController::class, 'chargeLogistique'])->name('charge-logistique');
            Route::get('/file-attente', [IndicateurDetailController::class, 'fileAttente'])->name('file-attente');
            Route::post('/file-attente/{id}/appeler', [IndicateurDetailController::class, 'appelerApprenant'])->name('file-attente.appeler');
            Route::post('/file-attente/session-mutualisee', [IndicateurDetailController::class, 'lancerSessionMutualisee'])->name('file-attente.session-mutualisee');
            Route::post('/file-attente/{id}/terminer', [IndicateurDetailController::class, 'terminerLiveCoding'])->name('file-attente.terminer');
            Route::get('/intervention', [IndicateurDetailController::class, 'intervention'])->name('intervention');
            Route::post('/intervention/intervenir', [IndicateurDetailController::class, 'enregistrerRemediation'])->name('intervention.intervenir');
            Route::post('/intervention/cloturer-live-coding', [IndicateurDetailController::class, 'cloturerLiveCodingCollectif'])->name('intervention.cloturer-live-coding');
            Route::get('/couverture', [IndicateurDetailController::class, 'couverture'])->name('couverture');
        });
    });
});
