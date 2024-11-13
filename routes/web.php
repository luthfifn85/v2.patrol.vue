<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatrolController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatrolPlanController;
use App\Http\Controllers\PatrolUserController;
use App\Http\Controllers\PatrolLocationController;
use App\Http\Controllers\PatrolCheckpointController;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'status' => session('status')
    ]);
});

Route::middleware('auth')->group(function () {

    // dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::name('dashboard')->group(function () {
                Route::get('/', 'index');
            });
        });
    });

    // user profile
    Route::controller(ProfileController::class)->group(function () {
        Route::prefix('profile')->group(function () {
            Route::name('profile')->group(function () {
                Route::get('/', 'index');
                Route::post('/update', 'update')->name('.update');
                Route::post('/password', 'password')->name('.password');
            });
        });
    });

    // patrol
    Route::controller(PatrolController::class)->group(function () {
        Route::prefix('patrol')->group(function () {
            Route::name('patrol')->group(function () {
                Route::get('/', 'index');
            });
        });
    });

    // company
    Route::controller(CompanyController::class)->group(function () {
        Route::prefix('company')->group(function () {
            Route::name('company')->group(function () {
                Route::get('/', 'index');
                Route::post('/store', 'store')->name('.store');
            });
        });
    });

    // location
    Route::controller(PatrolLocationController::class)->group(function () {
        Route::prefix('location')->group(function () {
            Route::name('location')->group(function () {
                Route::get('/', 'index');
                Route::post('/store', 'store')->name('.store');
                Route::put('/{id}/update', 'update')->name('.update');
            });
        });
    });

    // checkpoint
    Route::controller(PatrolCheckpointController::class)->group(function () {
        Route::prefix('checkpoint')->group(function () {
            Route::name('checkpoint')->group(function () {
                Route::get('/', 'index');
                Route::post('/store', 'store')->name('.store');
                Route::put('/{id}/update', 'update')->name('.update');
            });
        });
    });

    // guard
    Route::controller(PatrolUserController::class)->group(function () {
        Route::prefix('guard')->group(function () {
            Route::name('guard')->group(function () {
                Route::get('/', 'index');
                Route::post('/store', 'store')->name('.store');
                Route::put('/{id}/update', 'update')->name('.update');
                Route::put('/{id}/password', 'password')->name('.password');
                Route::put('/{id}/reset', 'reset')->name('.reset');
            });
        });
    });

    // plan
    Route::controller(PatrolPlanController::class)->group(function () {
        Route::prefix('plan')->group(function () {
            Route::name('plan')->group(function () {
                Route::get('/', 'index');
                Route::post('/store', 'store')->name('.store');
                Route::put('/{id}/update', 'update')->name('.update');
            });
        });
    });

    // user
    Route::controller(UserController::class)->group(function () {
        Route::prefix('users')->group(function () {
            Route::name('users.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}/update', 'update')->name('.update');
                Route::put('/{userBind}/change-password', 'changePassword')->name('change_password');
            });
        });
    });

    // log
    Route::controller(LogsController::class)->group(function () {
        Route::prefix('log')->group(function () {
            Route::name('log')->group(function () {
                Route::get('/', 'index');
            });
        });
    });

    // FAQs
    Route::controller(FaqsController::class)->group(function () {
        Route::prefix('faqs')->group(function () {
            Route::get('/', 'index')->name('faqs.index');
        });
    });

    // Report
    Route::controller(ReportController::class)->group(function () {
        Route::prefix('report')->group(function () {
            Route::name('report')->group(function () {
                Route::get('/', 'index');
            });
        });
    });
});

require __DIR__ . '/auth.php';
