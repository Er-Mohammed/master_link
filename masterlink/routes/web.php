<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::inertia('/', 'welcome')->name('home');

require __DIR__.'/admin.php';

require __DIR__.'/settings.php';

Route::get('/test-mail', function () {

    Mail::to('test@example.com')->send(new TestMail());

    return 'Email sent successfully!';

});