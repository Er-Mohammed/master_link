<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

//-------------------------------------//
use App\Http\Controllers\AdminController;

Route::resource('admins', AdminController::class);

//----------------------------//
use App\Http\Controllers\ClientLogoController;

Route::resource('client-logos', ClientLogoController::class);

//------------------------------//
use App\Http\Controllers\ConsultationController;

Route::resource('consultations', ConsultationController::class);

//----------------------------//
use App\Http\Controllers\MediaController;

Route::resource('media', MediaController::class);

//------------------------------//
use App\Http\Controllers\PostController;

Route::resource('posts', PostController::class);

//----------------------------//
use App\Http\Controllers\ProjectCategoryController;

Route::resource('project-categories', ProjectCategoryController::class);

//--------------------------//
use App\Http\Controllers\ProjectController;

Route::resource('projects', ProjectController::class);

//------------------------------//
use App\Http\Controllers\ProjectMediaController;

Route::prefix('projects/{project}')->name('projects.media.')->group(function () {
    Route::get('media', [ProjectMediaController::class, 'index'])->name('index');
    Route::post('media', [ProjectMediaController::class, 'store'])->name('store');
    Route::post('media/update-order', [ProjectMediaController::class, 'updateOrder'])->name('update-order');
    Route::delete('media/{media}', [ProjectMediaController::class, 'destroy'])->name('destroy');
});

//-----------------------------------------//
use App\Http\Controllers\ProjectServiceController;

Route::prefix('projects/{project}')->name('projects.services.')->group(function () {
    Route::get('services', [ProjectServiceController::class, 'index'])->name('index');
    Route::post('services/sync', [ProjectServiceController::class, 'sync'])->name('sync');
    Route::delete('services/{service}', [ProjectServiceController::class, 'destroy'])->name('destroy');
});

//--------------------------------//
use App\Http\Controllers\ServiceController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('services', ServiceController::class);
});

//------------------------------------//
use App\Http\Controllers\ServiceMediaController;

Route::prefix('admin')->name('admin.')->group(function () {
    // مسارات الخدمات الأساسية
    Route::resource('services', ServiceController::class);

    // مسارات وسائط الخدمات
    Route::post('services/{service}/media', [ServiceMediaController::class, 'store'])->name('services.media.store');
    Route::put('services/{service}/media/{media}', [ServiceMediaController::class, 'update'])->name('services.media.update');
    Route::delete('services/{service}/media/{media}', [ServiceMediaController::class, 'destroy'])->name('services.media.destroy');
});

//----------------------------------------//
use App\Http\Controllers\SiteSettingController;

Route::prefix('admin')->name('admin.')->group(function () {
    
    // مسارات إعدادات الموقع
    Route::get('settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SiteSettingController::class, 'update'])->name('settings.update');

});

//---------------------------//
use App\Http\Controllers\TestimonialController;

Route::prefix('admin')->name('admin.')->group(function () {
    
    // مسار آراء العملاء بالكامل (index, create, store, edit, update, destroy)
    Route::resource('testimonials', TestimonialController::class);

});