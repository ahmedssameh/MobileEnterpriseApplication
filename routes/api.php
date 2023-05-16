<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\BusinessServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=>'api','prefix'=>'Auth'],function ($router){
    Route::Post('/register',[AuthController::class,'register']);
    Route::Post('/login',[AuthController::class,'login']);
    Route::Patch('/update',[EditProfileController::class,'update']);
    Route::Patch('/changePassword',[EditProfileController::class,'changePassword']);
    Route::Get('/getCompany',[EditProfileController::class,'getCompany']);
    Route::Get('/getServices',[BusinessServiceController::class,'getServices']);
    Route::Post('/createService',[BusinessServiceController::class,'createService']);
    Route::Post('/favService',[BusinessServiceController::class,'favService']);
    Route::Get('/getFavoriteServices',[BusinessServiceController::class,'getFavoriteServices']);
    Route::Get('/getServiceCompany',[BusinessServiceController::class,'getServiceCompany']);
    Route::Get('/getCompanyServices',[BusinessServiceController::class,'getCompanyServices']);
});
