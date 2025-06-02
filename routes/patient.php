<?php

use App\Constants\RouteNames;
use App\Http\Controllers\Pateint\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Patient\PatientController;

/*
|--------------------------------------------------------------------------
| Doctor API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Patients API routes for patients in the system
|
*/

// No Auth Needed
Route::middleware([])->group(function () {
    
});

//Auth Needed
Route::group(['middleware' => ['auth:api', "is_user", 'token.access_api', 'user.active', 'user.verified']], function () {
        Route::controller(PatientController::class)->group(function(){callback: 
            Route::prefix('medical_profile')->group(function(){
                Route::post('store_mine' , 'createMyMedicalProfile');
                Route::post('store_others' , 'createMedicalProfile');
                Route::get('relations' , 'getRelations');
            });
        });
        Route::controller(DoctorController::class)->group(function(){
            Route::prefix('doctor')->group(function(){
                Route::get('filter' , 'get')->name(RouteNames::DOCTORS_FILTER_USER_SIDE);
                Route::get('profile/{id}' , 'profile')->name(RouteNames::DOCTORS_GET_PROFILE);
            });
        }); 
});