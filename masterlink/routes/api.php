<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ClientLogoController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\AdminController;

Route::prefix('admin')->group(function () {

    Route::apiResource('services', ServiceController::class);

    Route::apiResource('projects', ProjectController::class);

    Route::apiResource('posts', PostController::class);

    Route::apiResource('media', MediaController::class);

    Route::apiResource('client-logos', ClientLogoController::class);

    Route::apiResource('testimonials', TestimonialController::class);


    Route::apiResource('consultations', ConsultationController::class)
        ->only([
            'index',
            'show',
            'update',
            'destroy'
        ]);


    Route::apiResource('site-settings', SiteSettingController::class);

    Route::apiResource(
        'project-categories',
        ProjectCategoryController::class
    );

    Route::apiResource('admins', AdminController::class);

});