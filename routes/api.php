<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CController;
use App\Http\Controllers\Api\PController;
use App\Http\Controllers\Api\RController;
use App\Http\Controllers\Api\SController;
use App\Http\Controllers\Api\UController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;

Route::get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'welcome',
        'meta' => [
            'app_name' => 'patrol mobile app',
            'developer' => 'pt. absolute services',
            'api_version' => '2.0'
        ]
    ], 200);
});

// login
Route::post('/login', [LoginController::class, 'index']); // user login

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(HomeController::class)->group(function () {

        // home
        Route::get('/home', 'index')->middleware('inactive'); // view home pages
    });

    Route::controller(UController::class)->group(function () {

        // user profile
        Route::prefix('user')->group(function () {
            Route::get('/', 'index')->middleware('inactive'); // view user profile
            Route::post('/edit', 'edit'); // edit user profile
            Route::post('/password', 'password'); // change user password
            Route::post('/photo', 'photo'); // change user profile photo
        });
    });

    Route::controller(PController::class)->group(function () {

        // patrol
        Route::prefix('patrol')->group(function () {
            Route::get('/{uuid}/scan', 'scan')->middleware(['noAdmin', 'noClient']); // scan & verify patrol checkpoint with qrcode
            Route::post('/{id}/store', 'store')->middleware(['noAdmin', 'noClient']); // create new patrol
            Route::get('/{id}/show', 'show')->middleware('inactive'); // view patrol details
            Route::post('/{id}/comment', 'comment'); // create new patrol comments on details
        });
    });

    Route::controller(SController::class)->group(function () {

        // sos
        Route::prefix('sos')->group(function () {
            Route::get('/', 'index')->middleware('inactive'); // view sos
            Route::post('/{id}/store', 'store')->middleware(['noAdmin', 'noGuard', 'noClient']); // create new sos
            Route::get('/{id}/show', 'show')->middleware('inactive'); // view sos details
            Route::post('/{id}/comment', 'comment'); // create new sos comments on details
        });
    });

    Route::controller(CController::class)->group(function () {

        // checkpoint
        Route::prefix('checkpoint')->group(function () {
            Route::get('/', 'index')->middleware('inactive'); // view patrol checkpoint
            Route::post('/store', 'store')->middleware(['noAdmin', 'noGuard', 'noClient']); // create new patrol checkpoint
            Route::get('/{id}/show', 'show')->middleware('inactive'); // view patrol checkpoint details
            Route::post('/{id}/edit', 'edit')->middleware('noAdmin', 'noGuard', 'noClient'); // edit patrol checkpoint
        });
    });

    Route::controller(RController::class)->group(function () {
        Route::prefix('report')->group(function () {
            Route::get('/patrol', 'patrol')->middleware('inactive');
            Route::get('/sos', 'sos')->middleware('inactive');
            Route::get('/summary', 'summary')->middleware('inactive');
        });
    });

    // logout
    Route::post('/logout', [LogoutController::class, 'index']); // user logout
});
