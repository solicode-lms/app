<?php

use Illuminate\Support\Facades\Route;
use Modules\PkgRealisationProjets\Controllers\AffectationProjetController;

Route::middleware('auth')
    ->prefix('/admin/PkgRealisationProjets')
    ->group(function () {
        Route::get('affectationProjets/getDataHasEvaluateurs', [AffectationProjetController::class, 'getDataHasEvaluateurs'])
            ->name('affectationProjets.getDataHasEvaluateurs');
    });