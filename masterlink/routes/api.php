<?php

use App\Http\Controllers\Website\ClientLogoController;
use App\Http\Controllers\Website\ConsultationController;
use App\Http\Controllers\Website\ProjectCategoryController;
use App\Http\Controllers\Website\ProjectController;
use App\Http\Controllers\Website\ServiceController;
use App\Http\Controllers\Website\SiteSettingController;
use App\Http\Controllers\Website\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::post('consultations', [ConsultationController::class, 'store'])
    ->name('consultations.store');

Route::get('projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('project-categories', [ProjectCategoryController::class, 'index'])
    ->name('project-categories.index');

Route::get('client-logos', [ClientLogoController::class, 'index'])
    ->name('client-logos.index');

Route::get('services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::get('site-settings', [SiteSettingController::class, 'index'])
    ->name('site-settings.index');

Route::get('testimonials', [TestimonialController::class, 'index'])
    ->name('testimonials.index');

require __DIR__.'/admin.php';
