<?php

use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\Doctor\PlanController;
use App\Http\Controllers\Doctor\ShiftController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\ArticleController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Doctor\ReservationController;
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
        Route::get('show/{id}' , 'show')->name(RouteNames::ARTICLES_SHOW);
    });
    Route::prefix('profile')->controller(DoctorController::class)->group(function () {
        Route::get('getMine' , 'getMyProfile')->name(RouteNames::DOCTORS_GET_PROFILE);
    });
    Route::prefix( 'article/reactions')->controller(ReactionController::class)->group(function(){ 
        Route::get('like/{article_id}' , 'like');
        Route::get('unLike/{article_id}' , 'unLike');
        Route::post('comment/{article_id}' , 'comment');
        Route::get('unComment/{comment_id}' , 'unComment');
        Route::get('likes/{article_id}' , 'getLikes');
        Route::get('comments/{article_id}' , 'getCommentsForUser');
        Route::post('replay/{comment_id}' , 'replay');
        Route::get('unReplay/{replay_id}' , 'unReplay');
    });

    Route::prefix('reservation')->group(function(){
        Route::controller(ReservationController::class)->group(function(){
            Route::post('reject/{id}' , 'reject');
            Route::post('accept/{id}' , 'accept');
            Route::get('did_not_come/{id}' , 'did_not_come');
            Route::post('done/{id}' , 'done');
            Route::get('get' , 'getReservations')->name(RouteNames::DOCTOR_RESERVATIONS);
            Route::get('details/{id}' , 'getReservationDetails')->name(RouteNames::RESERVATION_DETAILS);
            Route::post('update/{visit_id}' , 'updateReport');
            Route::post('rateReplay/{rate_id}' , 'replayOnRate');

            Route::prefix('medicalReport')->group(function(){
                Route::prefix('medicine')->group(function(){
                    Route::post('add/{patient_id}/visit/{visit_id}' , 'addMedicines');
                    
                    Route::post('{patient_id}/update/{medicine_id}/visit/{visit_id}' , 'updateMedicine');

                    Route::delete('delete/{medicine_id}/visit/{visit_id}' , 'deleteMedicine');
                });
                Route::prefix('instruction')->group(function(){
                    Route::post('add/{patient_id}/visit/{visit_id}' , 'addInstructions');
                    
                    Route::post('{patient_id}/update/{instruction_id}/visit/{visit_id}' , 'updateInstruction');
                    
                    Route::delete('delete/{instruction_id}/visit/{visit_id}' , 'deleteInstruction');
                });
            });
        });
    });


    Route::apiResource('/article', ArticleController::class);
    Route::apiResource('/shift', ShiftController::class)
        ->name('index' , RouteNames::DOCTOR_SHIFT_GET)
        ->name('show' , RouteNames::DOCTOR_SHIFT_SHOW);
});