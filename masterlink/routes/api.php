<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\ConsultationController;
use App\Http\Controllers\Website\ProjectController;
use App\Http\Controllers\Website\ClientLogoController;
use App\Http\Controllers\Website\ServiceController;

Route::post('consultations', [ConsultationController::class, 'store'])
    ->name('consultations.store');

Route::get('projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('client-logos', [ClientLogoController::class, 'index'])
    ->name('client-logos.index');

Route::get('services', [ServiceController::class, 'index'])
    ->name('services.index');

require __DIR__ . '/admin.php';
