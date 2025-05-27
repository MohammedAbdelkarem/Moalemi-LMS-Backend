<?php

use App\Constants\RouteNames;
use App\Http\Controllers\Doctor\ArticleController;
use App\Http\Controllers\Doctor\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\PlanController;
use App\Http\Controllers\Doctor\TransactionController;

/*
|--------------------------------------------------------------------------
| Doctor API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Doctors API routes for doctors in the system
|
*/

// No Auth Needed
Route::middleware([])->group(function () {
    
});

//Auth Needed
Route::group(['middleware' => ['auth:api', "is_user", 'token.access_api', 'user.active', 'user.verified']], function () {
    Route::prefix('subscription')->controller(PlanController::class)->group(function () {
        Route::get('subscripe/{id}' , 'subscripe');
        Route::get('get' , 'getSubscriptions');
    });

    Route::prefix('plans')->controller(PlanController::class)->group(function () {
        Route::get('get' , 'getPlans');
    });

    Route::prefix('transactions')->controller(TransactionController::class)->group(function () {
        Route::get('get' , 'getMyTransactions')->name(RouteNames::DOCTOR_TRANSACTION_GET);
    });

    Route::prefix('phone_numbers')->controller(DoctorController::class)->group(function () {
        Route::post('store' , 'addPhoneNumbers');
        Route::post('update/{id}' , 'updatePhoneNumber');
        Route::delete('delete' , 'deletePhoneNumbers');
    });
    Route::prefix('article')->controller(ArticleController::class)->group(function () {
        Route::get('getMine' , 'getMyArticles');
    });


    Route::apiResource('/article', ArticleController::class);
});