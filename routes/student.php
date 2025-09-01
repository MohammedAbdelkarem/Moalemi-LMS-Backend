<?php

use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\HomeController;
use App\Http\Controllers\Mobile\HirarichyController;

/*
|--------------------------------------------------------------------------
| Doctor API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Doctors API routes for doctors in the system
|
*/

// No Auth Needed
Route::middleware([])->withoutMiddleware('is_student')->group(function () {
    Route::prefix('e-levels')->controller(HirarichyController::class)->group(function () {
        Route::get('/', 'e_levels');
    });
    Route::prefix('c-levels')->controller(HirarichyController::class)->group(function () {
        Route::get('/{e_level_id}', 'c_levels');
    });
});

//Auth Needed
Route::group(['middleware' => ['auth:api', "is_user", 'token.access_api', 'user.active', 'user.verified']], function () {
    Route::prefix('home')->controller(HomeController::class)->group(function () {
        Route::get('/', 'home')->name(RouteNames::STUDENT_HOME);
    });
});