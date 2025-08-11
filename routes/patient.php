<?php

use App\Models\SubCategory;
use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\Patient\ListController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Pateint\DoctorController;
use App\Http\Controllers\Patient\HealthController;
use App\Http\Controllers\Patient\ArticleController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Patient\FavoriteController;
use App\Http\Controllers\System\Info\CityController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Patient\ReservationController;
use App\Services\Patient\PatientService;

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
    

});