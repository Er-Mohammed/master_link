<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClientLogoController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ProjectCategoryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:admin-login')
            ->name('login');

        /*
        |--------------------------------------------------------------------------
        | Protected Admin API
        |--------------------------------------------------------------------------
        */

        Route::middleware([
            'auth:sanctum',
            'active.admin',
        ])->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            Route::get('me', [AuthController::class, 'me'])
                ->name('me');

            Route::post('logout', [AuthController::class, 'logout'])
                ->name('logout');

            Route::post(
                'change-password',
                [AuthController::class, 'changePassword']
            )->name('change-password');

            /*
            |--------------------------------------------------------------------------
            | Services
            |--------------------------------------------------------------------------
            */

            Route::apiResource('services', ServiceController::class)
                ->middleware('role:super_admin,admin');

            Route::post(
                'services/{service}/media',
                [ServiceController::class, 'attachMedia']
            )
                ->middleware('role:super_admin,admin')
                ->name('services.media.attach');

            Route::delete(
                'services/{service}/media/{media}',
                [ServiceController::class, 'detachMedia']
            )
                ->middleware('role:super_admin,admin')
                ->name('services.media.detach');

            /*
            |--------------------------------------------------------------------------
            | Projects
            |--------------------------------------------------------------------------
            */

            Route::apiResource('projects', ProjectController::class)
                ->middleware('role:super_admin,admin,marketing');

            /*
            |--------------------------------------------------------------------------
            | Project Categories
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'project-categories',
                ProjectCategoryController::class
            )->middleware('role:super_admin,admin');

            /*
            |--------------------------------------------------------------------------
            | Posts
            |--------------------------------------------------------------------------
            */

            Route::apiResource('posts', PostController::class)
                ->middleware('role:super_admin,admin,content_manager');

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            Route::apiResource('media', MediaController::class)
                ->middleware('role:super_admin,admin,content_manager');

            /*
            |--------------------------------------------------------------------------
            | Client Logos
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'client-logos',
                ClientLogoController::class
            )->middleware('role:super_admin,admin,marketing');

            /*
            |--------------------------------------------------------------------------
            | Testimonials
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'testimonials',
                TestimonialController::class
            )->middleware('role:super_admin,admin,content_manager');

            /*
            |--------------------------------------------------------------------------
            | Consultations
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'consultations',
                ConsultationController::class
            )
                ->only([
                    'index',
                    'show',
                    'update',
                    'destroy',
                ])
                ->middleware('role:super_admin,admin,marketing');

            /*
            |--------------------------------------------------------------------------
            | Site Settings
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'site-settings',
                SiteSettingController::class
            )->middleware('role:super_admin');

            /*
            |--------------------------------------------------------------------------
            | Admin Management
            |--------------------------------------------------------------------------
            */

            Route::apiResource(
                'admins',
                AdminController::class
            )->middleware('role:super_admin');
        });
    });
