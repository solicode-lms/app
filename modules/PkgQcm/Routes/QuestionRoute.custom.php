<?php
// Ce fichier permet de définir des routes personnalisées prioritaires pour les Questions.

use Illuminate\Support\Facades\Route;
use Modules\PkgQcm\Controllers\QuestionController;

// Routes pour l'import des questions via IA déplacées vers AffectationQcmProjetRoute.custom.php
Route::group(['middleware' => ['auth']], function () {
    // Les anciennes routes 'questions/import-ia' ont été supprimées
});
