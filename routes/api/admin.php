<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthAdminController;
use App\Http\Controllers\Admin\AdminController;

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
Route::group(['prefix' => 'admin'] , function(){
Route::post('login' ,[AuthAdminController::class , 'login'] );
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api' , 'scopes:admin']] , function(){
   
   //owner
    Route::get('show-license' ,[AdminController::class , 'showLicense'] );
    Route::get('approv/{licenseId}' ,[AdminController::class , 'licenseApproval'] );
    Route::get('rejected/{licenseId}' ,[AdminController::class , 'licenserejected'] );

    //freelancer
    Route::get('show-license' ,[AdminController::class , 'showFreelancerLicense'] );
    Route::get('approv/{licenseId}' ,[AdminController::class , 'licenseFreelancerApproval'] );
    Route::get('rejected/{licenseId}' ,[AdminController::class , 'licenseFreelancerRejected'] );

});