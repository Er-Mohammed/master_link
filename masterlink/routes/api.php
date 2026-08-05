<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Auth Controller
use App\Http\Controllers\Api\AdminAuthController;

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

/*
|--------------------------------------------------------------------------
| Public Auth Routes (مفتوح للجميع)
|--------------------------------------------------------------------------
*/
Route::post('/admin/login', [AdminAuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Protected Admin Routes (محمي بـ Sanctum Token)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {

    // Auth Management
    Route::post('/logout', [AdminAuthController::class, 'logout']);
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    /*
    |--------------------------------------------------------------------------
    | Services Media Management
    |--------------------------------------------------------------------------
    */
    Route::post(
        'services/{service}/media',
        [ServiceController::class, 'attachMedia']
    )->name('services.media.attach');

    Route::delete(
        'services/{service}/media/{media}',
        [ServiceController::class, 'detachMedia']
    )->name('services.media.detach');


    /*
    |--------------------------------------------------------------------------
    | API Resources
    |--------------------------------------------------------------------------
    */
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('project-categories', ProjectCategoryController::class);
    Route::apiResource('posts', PostController::class);
    
    Route::apiResource('media', MediaController::class)->parameters([
        'media' => 'media'
    ]);
    
    Route::apiResource('client-logos', ClientLogoController::class);
    Route::apiResource('testimonials', TestimonialController::class);
    
    Route::apiResource('consultations', ConsultationController::class)->only([
        'index', 'show', 'update', 'destroy'
    ]);
    
    Route::apiResource('site-settings', SiteSettingController::class);
    Route::apiResource('admins', AdminController::class);

});