<?php

use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::controller(EmailController::class)->prefix('emails')->group(function () {
    Route::get('/', 'index');
    Route::get('/send_now/{id}', 'sendNow');
    Route::post('/', 'store');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'delete');
});
