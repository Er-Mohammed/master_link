<?php

use Illuminate\Support\Facades\Route;

// Admin Controllers
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


    /*
    |--------------------------------------------------------------------------
    | Services Media Management
    |--------------------------------------------------------------------------
    */

    Route::post(
        'services/{service}/media',
        [ServiceController::class, 'attachMedia']
    )
    ->name('services.media.attach');


    Route::delete(
        'services/{service}/media/{media}',
        [ServiceController::class, 'detachMedia']
    )
    ->name('services.media.detach');



    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'services',
        ServiceController::class
    );



    /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'projects',
        ProjectController::class
    );



    /*
    |--------------------------------------------------------------------------
    | Project Categories
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'project-categories',
        ProjectCategoryController::class
    );



    /*
    |--------------------------------------------------------------------------
    | Posts
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'posts',
        PostController::class
    );



    /*
    |--------------------------------------------------------------------------
    | Media Files
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'media',
        MediaController::class
    )
    ->parameters([
        'media' => 'media'
    ]);



    /*
    |--------------------------------------------------------------------------
    | Client Logos
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'client-logos',
        ClientLogoController::class
    );



    /*
    |--------------------------------------------------------------------------
    | Testimonials
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'testimonials',
        TestimonialController::class
    );



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
        'destroy'
    ]);



    /*
    |--------------------------------------------------------------------------
    | Website Settings
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'site-settings',
        SiteSettingController::class
    );



    /*
    |--------------------------------------------------------------------------
    | Admin Management
    |--------------------------------------------------------------------------
    */

    Route::apiResource(
        'admins',
        AdminController::class
    );


});