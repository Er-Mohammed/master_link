<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ServiceController;


Route::prefix('admin')->group(function () {

    Route::resource('services', ServiceController::class);

});