<?php
// Ce fichier permet de définir des routes personnalisées prioritaires pour les Questions.

use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\QuestionController;

// Routes pour l'import des questions via IA
Route::group(['middleware' => ['auth']], function () {
    Route::get('questions/import-ia', [QuestionController::class, 'importIaForm'])->name('questions.importIaForm');
    Route::post('questions/import-ia', [QuestionController::class, 'importIaProcess'])->name('questions.importIaProcess');
});
