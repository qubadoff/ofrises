<?php

use App\Http\Controllers\api\Auth\AuthController;
use App\Http\Controllers\api\Company\CompanyController;
use App\Http\Controllers\api\Customer\CustomerController;
use App\Http\Controllers\api\Support\SupportController;
use App\Http\Controllers\api\Worker\WorkerController;
use App\Http\Controllers\api\Worker\WorkerRequestController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);

    Route::prefix('resetPass')->group(function () {
        Route::post('/requestReset', [AuthController::class, 'requestReset']);
        Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('customers')->group(function () {
        Route::get('/me', [CustomerController::class, 'me']);
        Route::get('/logout', [CustomerController::class, 'logout']);
        Route::post('/updatePhoto', [CustomerController::class, 'updatePhoto']);
        Route::post('/updatePassword', [CustomerController::class, 'updatePassword']);
        Route::post('/updateProfile', [CustomerController::class, 'updateProfile']);
        Route::post('/deleteProfile', [CustomerController::class, 'deleteProfile']);
    });

    Route::prefix('support')->group(function () {
        Route::get('/contactInfo', [SupportController::class, 'contactInfo']);
        Route::get('/faqs', [SupportController::class, 'faqs']);
        Route::get('/privacyAndPolicy', [SupportController::class, 'privacyAndPolicy']);
    });

    Route::prefix('workers')->group(function () {
        Route::get('/list', [WorkerController::class, 'list']);
        Route::get('/workerCVList', [WorkerRequestController::class, 'workerCVList']);
        Route::post('/create', [WorkerRequestController::class, 'create']);
        Route::post('/uploadPhotoAndVideo', [WorkerRequestController::class, 'uploadPhotoAndVideo']);
        Route::middleware(SetLocale::class)->group(function () {
            Route::get('/workArea', [WorkerController::class, 'workArea']);
            Route::get('/workType', [WorkerController::class, 'workType']);
            Route::get('/currencies', [WorkerController::class, 'currencies']);
            Route::get('/salaryType', [WorkerController::class, 'salaryType']);
            Route::get('/workExpectation', [WorkerController::class, 'workExpectation']);
            Route::get('/citizenship', [WorkerController::class, 'citizenship']);
            Route::get('/maritalStatus', [WorkerController::class, 'maritalStatus']);
            Route::get('/militaryStatus', [WorkerController::class, 'militaryStatus']);
            Route::get('/driverLicense', [WorkerController::class, 'driverLicense']);
            Route::get('/carModels', [WorkerController::class, 'carModels']);
            Route::get('/languages', [WorkerController::class, 'languages']);
            Route::get('/educationType', [WorkerController::class, 'educationType']);
            Route::get('/hobbies', [WorkerController::class, 'hobbies']);
            Route::get('/softSkills', [WorkerController::class, 'softSkills']);
            Route::get('/hardSkills', [WorkerController::class, 'hardSkills']);
        });
    });

    Route::prefix('company')->group(function () {
        Route::get('/getCompany', [CompanyController::class, 'getCompany']);
        Route::post('/create', [CompanyController::class, 'create']);
        Route::post('/createImages', [CompanyController::class, 'createImages']);
    });
});
