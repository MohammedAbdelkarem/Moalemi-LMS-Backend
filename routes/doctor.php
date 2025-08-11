<?php

use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\Doctor\PlanController;
use App\Http\Controllers\Media\MediaController;
use App\Http\Controllers\Doctor\ShiftController;
use App\Http\Controllers\Patient\ListController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Doctor\ArticleController;
use App\Http\Controllers\Doctor\PatientController;
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
Route::middleware([])->withoutMiddleware('is_doctor')->group(function () {
    
});

//Auth Needed
Route::group(['middleware' => ['auth:api', "is_user", 'token.access_api', 'user.active', 'user.verified']], function () {
    
});