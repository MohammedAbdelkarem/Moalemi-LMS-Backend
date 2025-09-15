<?php

use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\Comment\CommentController;
use App\Http\Controllers\Mobile\LessonQuestion\LessonQuestionController;

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
    // Comments
    Route::prefix('comments')->controller(CommentController::class)->group(function () {
        Route::get('/{lesson_id}', 'index')->name(RouteNames::MOBILE_COMMENTS_LIST);
        Route::post('/replay/{comment_id}', 'replay');
        Route::delete('/replay/{id}', 'deleteReplay');
        Route::post('/{lesson_id}', 'store');
        Route::delete('/{id}', 'deleteComment');
        Route::post('/pin/{id}', 'pinComment');
    });

    // Lesson Questions
    Route::prefix('lesson-questions')->controller(LessonQuestionController::class)->group(function () {
        Route::get('/{lesson_id}', 'index');
        Route::post('/{question_id}', 'answer');
    });
});