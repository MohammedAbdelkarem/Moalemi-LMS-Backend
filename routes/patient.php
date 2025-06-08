<?php

use App\Models\SubCategory;
use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Pateint\DoctorController;
use App\Http\Controllers\Patient\ArticleController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Patient\FavoriteController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Patient\ListController;
use App\Http\Controllers\System\Info\CityController;

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
            Route::prefix('update')->group(function(){
                Route::post('profile/{id}' , 'updateInfo');
                Route::post('medicines/{id}' , 'updateMedicines');
                Route::post('instructions/{id}' , 'updateInstructions');
            });
        });
    });
    Route::controller(DoctorController::class)->group(function(){
        Route::prefix('doctor')->group(function(){
            Route::get('filter' , 'get')->name(RouteNames::DOCTORS_FILTER_USER_SIDE);
            Route::get('profile/{id}' , 'profile')->name(RouteNames::DOCTORS_GET_PROFILE);
        });
    }); 
    Route::controller(ArticleController::class)->group(function(){
        Route::prefix('article')->group(function(){
            Route::get('list' , 'list')->name(RouteNames::ARTICLES_LIST);
            Route::get('search' , 'search');
            Route::get('show/{id}' , 'show')->name(RouteNames::ARTICLES_SHOW);
        });
    }); 
    Route::controller(ReactionController::class)->group(function(){
        Route::prefix('article/reactions')->group(function(){
            Route::get('like/{article_id}' , 'like');
            Route::get('unLike/{article_id}' , 'unLike');
            Route::post('comment/{article_id}' , 'comment');
            Route::get('unComment/{comment_id}' , 'unComment');
            Route::get('likes/{article_id}' , 'getLikes');
            Route::get('comments/{article_id}' , 'getCommentsForUser');
        });
    }); 
    Route::prefix('favorite')->controller(FavoriteController::class)->group(function(){
        Route::get('get' , 'get');
        Route::prefix('set')->group(function(){
            Route::post('doctor/{id}' , 'setDoctorAsFavorite');
            Route::post('article/{id}' , 'setArticleAsFavorite');
        });
        Route::prefix('unset')->group(function(){
            Route::post('doctor/{id}' , 'setDoctorAsUnFavorite');
            Route::post('article/{id}' , 'setArticleAsUnFavorite');
        });
    });

    Route::prefix('list')->group(function(){
        Route::controller(ListController::class)->group(function(){
            Route::get('categories' , 'categories');
            Route::get('subcategories' , 'subcategories');
            Route::get('cities' , 'cities');
        });
    });
});