<?php

use App\Constants\RouteNames;
use Illuminate\Support\Facades\Route;

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