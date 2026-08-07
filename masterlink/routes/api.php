<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController;
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

    Route::post('login', [AuthController::class, 'login'])
        ->name('admin.login');

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('me', [AuthController::class, 'me'])
            ->name('admin.me');

        Route::post('logout', [AuthController::class, 'logout'])
            ->name('admin.logout');

        Route::post(
            'services/{service}/media',
            [ServiceController::class, 'attachMedia']
        )->middleware('role:super_admin,admin');

        Route::delete(
            'services/{service}/media/{media}',
            [ServiceController::class, 'detachMedia']
        )->middleware('role:super_admin,admin');

        Route::apiResource(
            'services',
            ServiceController::class
        )->middleware('role:super_admin,admin');

        Route::apiResource(
            'projects',
            ProjectController::class
        )->middleware('role:super_admin,admin,marketing');

        Route::apiResource(
            'project-categories',
            ProjectCategoryController::class
        )->middleware('role:super_admin,admin');

        Route::apiResource(
            'posts',
            PostController::class
        )->middleware('role:super_admin,admin,content_manager');

        Route::apiResource(
            'media',
            MediaController::class
        )->middleware('role:super_admin,admin,content_manager,marketing');

        Route::apiResource(
            'client-logos',
            ClientLogoController::class
        )->middleware('role:super_admin,admin,marketing');

        Route::apiResource(
            'testimonials',
            TestimonialController::class
        )->middleware('role:super_admin,admin,content_manager');

        Route::apiResource(
            'consultations',
            ConsultationController::class
        )
        ->only(['index', 'show', 'update', 'destroy'])
        ->middleware('role:super_admin,admin,marketing');

        Route::apiResource(
            'site-settings',
            SiteSettingController::class
        )->middleware('role:super_admin');

        Route::apiResource(
            'admins',
            AdminController::class
        )->middleware('role:super_admin');

    });

});