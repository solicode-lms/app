<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
