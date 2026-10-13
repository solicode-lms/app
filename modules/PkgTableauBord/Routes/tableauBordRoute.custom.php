<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgTableauBord\Controllers\ActiviteFormateurController;
use Modules\PkgTableauBord\Controllers\TableauBordApprenantController;
use Modules\PkgTableauBord\Controllers\TableauBordFormateurController;
use Modules\PkgTableauBord\Controllers\TableauBordRechercheController;
use Modules\PkgTableauBord\Controllers\TableauBordResponsableController;

Route::middleware('auth')->group(function () {
    Route::prefix('tableau-bord')->name('tableau-bord.')->group(function () {
        Route::get('/apprenant', [TableauBordApprenantController::class, 'index'])->name('apprenant');
        Route::get('/formateur', [TableauBordFormateurController::class, 'index'])->name('formateur');
        Route::get('/responsable', [TableauBordResponsableController::class, 'index'])->name('responsable');
        Route::get('/recherche', [TableauBordRechercheController::class, 'index'])->name('recherche');
        Route::post('/telemetrie/activite', [ActiviteFormateurController::class, 'ping'])->name('telemetrie.activite');
    });

    Route::prefix('admin/PkgTableauBord')->name('admin.PkgTableauBord.')->group(function () {
        Route::get('/apprenant', [TableauBordApprenantController::class, 'index'])->name('apprenant');
        Route::get('/formateur', [TableauBordFormateurController::class, 'index'])->name('formateur');
        Route::get('/responsable', [TableauBordResponsableController::class, 'index'])->name('responsable');
        Route::get('/recherche', [TableauBordRechercheController::class, 'index'])->name('recherche');
        Route::post('/telemetrie/activite', [ActiviteFormateurController::class, 'ping'])->name('telemetrie.activite');
    });
});
