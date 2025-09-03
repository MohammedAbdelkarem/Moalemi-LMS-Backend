<?php

use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\HomeController;
use App\Http\Controllers\Mobile\HirarichyController;
use App\Http\Controllers\Mobile\HierarichyController;

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
    Route::prefix('hierarichy')->controller(HierarichyController::class)->group(function () {
        Route::get('/subject/{subject_id}', 'getSubject')->name(RouteNames::MOBILE_HIERARICHY_SUBJECT);
        Route::get('/unit/{unit_id}', 'getUnit')->name(RouteNames::MOBILE_HIERARICHY_UNIT);
        Route::get('/sub-unit/{sub_unit_id}', 'getSubUnit')->name(RouteNames::MOBILE_HIERARICHY_SUB_UNIT);
        Route::get('/unit-details/{unit_id}', 'getUnitDetails')->name(RouteNames::MOBILE_HIERARICHY_UNIT_DETAILS);
        Route::get('/responsibilities-by-teacher/{teacher_id}', 'getResponsibilitiesByTeacherId')->name(RouteNames::MOBILE_HIERARICHY_RESPONSIBILITIES_BY_TEACHER_ID);
        Route::post('/lesson/{lesson_id}/mark-watched', 'markLessonAsWatched')->name(RouteNames::MOBILE_HIERARICHY_MARK_LESSON_WATCHED);
    });
});