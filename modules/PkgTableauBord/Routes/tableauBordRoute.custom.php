<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgTableauBord\Controllers\TableauBordApprenantController;
use Modules\PkgTableauBord\Controllers\TableauBordFormateurController;
use Modules\PkgTableauBord\Controllers\TableauBordResponsableController;

Route::middleware('auth')->group(function () {
    Route::prefix('tableau-bord')->name('tableau-bord.')->group(function () {
        Route::get('/apprenant', [TableauBordApprenantController::class, 'index'])->name('apprenant');
        Route::get('/formateur', [TableauBordFormateurController::class, 'index'])->name('formateur');
        Route::get('/responsable', [TableauBordResponsableController::class, 'index'])->name('responsable');
    });

    Route::prefix('admin/PkgTableauBord')->name('admin.PkgTableauBord.')->group(function () {
        Route::get('/apprenant', [TableauBordApprenantController::class, 'index'])->name('apprenant');
        Route::get('/formateur', [TableauBordFormateurController::class, 'index'])->name('formateur');
        Route::get('/responsable', [TableauBordResponsableController::class, 'index'])->name('responsable');
    });
});
