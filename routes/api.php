<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
        Route::get('/home', 'index'); // view homa pages

        // user profile
        Route::prefix('user')->group(function () {
            Route::post('/edit', 'edit'); // edit user profile
            Route::post('/password', 'password'); // change user password
            Route::post('/photo', 'photo'); // change user profile photo
        });
        
        // patrol
        Route::prefix('patrol')->group(function () {
            Route::post('/{id}/store', 'store'); // create new patrol
            Route::get('/{id}/show', 'patrol'); // view patrol details
            Route::post('/{id}/comment', 'comment'); // create new patrol comments on details
            Route::get('/{id}/scan', 'scan'); // scan & verify patrol checkpoint with qrcode
        });
    });

    Route::controller(SettingController::class)->group(function () {
        Route::prefix('setting')->group(function () {
            
            // checkpoint
            Route::prefix('checkpoint')->group(function () {
                Route::get('/', 'cindex'); // view patrol checkpoint
                Route::post('/store', 'cstore'); // create new patrol checkpoint
                Route::get('/{id}/show', 'cshow'); // view patrol checkpoint details
                Route::post('/{id}/edit', 'cedit'); // edit patrol checkpoint
            });
        });
    });

    // logout
    Route::post('/logout', [LogoutController::class, 'index']); // user logout
});