<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ServiceController;


Route::prefix('admin')
    ->name('admin.')
    ->group(function () {


        Route::resource('services', ServiceController::class);


    });