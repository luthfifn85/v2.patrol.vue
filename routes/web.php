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

Route::get('/terms-policy', function () {
    return Inertia::render('Terms/Index');
})->name('terms_policy');


Route::middleware('auth')->group(function () {

    // dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::name('dashboard.')->group(function () {
                Route::get('/', 'index')->name('index');
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
        Route::prefix('patrols')->group(function () {
            Route::name('patrols.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{patrolBind}', 'show')->name('show');
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
        Route::prefix('locations')->group(function () {
            Route::name('locations.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{locationBind}', 'update')->name('update');
            });
        });
    });

    // checkpoint
    Route::controller(PatrolCheckpointController::class)->group(function () {
        Route::prefix('checkpoints')->group(function () {
            Route::name('checkpoints.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{checkpointBind}', 'update')->name('update');
                Route::put('/{checkpointBind}/qr-code', 'generateQRCode')->name('generate_qr_code');
            });
        });
    });

    // guard
    Route::controller(PatrolUserController::class)->group(function () {
        Route::prefix('guards')->group(function () {
            Route::name('guards.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{patrolUserBind}', 'update')->name('update');
                Route::put('/{patrolUserBind}/change-password', 'changePassword')->name('change_password');
                Route::put('/{patrolUserBind}/reset', 'resetSession')->name('reset_session');
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
