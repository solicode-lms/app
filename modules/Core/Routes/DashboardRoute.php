<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\DashboardController;

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name("dashbaord");
});

Route::redirect('/admin', '/admin/PkgWidgets/widgetUtilisateurs')->name('admin.home');

// adminlte-lab
Route::get('/adminlte-lab', [DashboardController::class, 'adminlte_lab'])->name("adminlte-lab");
