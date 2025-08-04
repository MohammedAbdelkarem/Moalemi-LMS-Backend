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
    Route::controller(PatientController::class)->group(function(){ 
        Route::prefix('medical_profile')->group(function(){
            Route::post('store_mine' , 'createMyMedicalProfile');
            Route::post('store_others' , 'createMedicalProfile');
            Route::get('relations' , 'getRelations')->name(RouteNames::PATIENT_RELATIONS);
            Route::get('permanents/{id}' , 'getPermanentProfile')->name(RouteNames::PATIENT_PERMANENT_PROFILE);
            Route::prefix('update')->group(function(){
                Route::post('profile/{id}' , 'updateInfo');
            });
            Route::prefix('medicine')->group(function(){
                Route::post('add/{patient_id}' , 'addMedicines');
                
                Route::post('{patient_id}/update/{medicine_id}' , 'updateMedicine');

                Route::delete('delete/{medicine_id}' , 'deleteMedicine');
            });
            Route::prefix('instruction')->group(function(){
                Route::post('add/{patient_id}' , 'addInstructions');
                 
                Route::post('{patient_id}/update/{instruction_id}' , 'updateInstruction');
                
                Route::delete('delete/{instruction_id}' , 'deleteInstruction');
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
            Route::post('comment/{article_id}' , 'comment')->middleware('user.banned');
            Route::get('unComment/{comment_id}' , 'unComment');
            Route::get('likes/{article_id}' , 'getLikes');
            Route::get('comments/{article_id}' , 'getCommentsForUser');
        });
    }); 
    Route::prefix('favorite')->controller(FavoriteController::class)->group(function(){
        Route::get('get' , 'get');
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

    Route::prefix('category')->group(function(){
        Route::controller(CategoryController::class)->group(function(){callback: 
            Route::get('show/{id}' , 'show');
        });
    });

    Route::prefix('reservation')->group(function(){
        Route::controller(ReservationController::class)->group(function(){
            Route::post('appoint' , 'appoint')->middleware('user.banned');
            Route::get('cancel/{id}' , 'cancel');
            Route::post('rate/{id}' , 'rate')->middleware('user.banned');;
            Route::get('get' , 'getReservations')->name(RouteNames::RESERVATION_DETAILS);
            Route::get('details/{id}' , 'getReservationDetails')->name(RouteNames::RESERVATION_DETAILS);
            Route::get('next/{patient_id}/{doctor_id}' , 'getNextReservation');
        });
    });

    Route::prefix('complaints')->group(function(){
        Route::controller(ComplaintController::class)->group(function(){
            Route::post('store' , 'complaint');
        });
    });

    Route::prefix('health')->group(function(){
        Route::controller(HealthController::class)->group(function(){
            Route::prefix('weight')->group(function(){
                Route::get('history' , 'getWeightHistory');
                Route::get('store/{current_weight}' , 'updateWeight');
            });
            Route::prefix('bmi')->group(function(){
                Route::get('get' , 'getBMI');
            });
            Route::prefix('water')->group(function(){
                Route::get('history' , 'getWaterHistory');
                Route::get('goal' , 'getWaterGoal');
                Route::post('store' , 'storeWater');
            });
            Route::prefix('sleep')->group(function(){
                Route::get('history' , 'getSleepHistory');
                Route::get('store/{amount}' , 'storeSleep');
            });
            Route::prefix('step')->group(function(){
                Route::get('history' , 'getStepHistory');
                Route::get('date' , 'getStepByDate');
                Route::post('store' , 'storeStep');
                Route::get('activate/{id}' , 'setStepCalcAsActive');
            });
        });
    });

    Route::prefix('history')->controller(TreatmentController::class)->group(function(){
        Route::get('medicine/{medicine_id}' , 'getMedicineHistory')->name(RouteNames::TREATMENT_DETAILS);
        Route::get('instruction/{instruction_id}' , 'getInstructionHistory')->name(RouteNames::TREATMENT_DETAILS);
    });

    Route::prefix('expired')->controller(TreatmentController::class)->group(function(){
        Route::get('medicine/{patient_id}' , 'getExpiredMedicine')->name(RouteNames::TREATMENT_DETAILS);
        Route::get('instruction/{patient_id}' , 'getExpiredInstruction')->name(RouteNames::TREATMENT_DETAILS);
    });

    Route::prefix('home')->controller(PatientController::class)->group(function(){
        Route::get('' , 'home')->name(RouteNames::PATIENT_HOME);
    });

    Route::prefix('notfication-management')->controller(PatientController::class)->group(function(){
        Route::get('' , 'getNotificationSettings');
        Route::post('update/{id}/{type}' , 'updateNotificationSetting');
    });


});