<?php

use App\Http\Controllers\Seeker\Auth\AuthSeekerController;

use App\Http\Controllers\Seeker\Auth\ResetPasswordSeekerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendshipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['prefix' =>'seeker' ], function(){

Route::post('/register' ,[AuthSeekerController::class , 'register']);
 Route::post('/login' ,[AuthSeekerController::class , 'login'] );
 Route::post('/verify' ,[AuthSeekerController::class , 'verifyEmail'] );
 Route::post('/forgetpassword' ,[ResetPasswordSeekerController::class , 'forgotPassword']);
 Route::post('/check-code' ,[ResetPasswordSeekerController::class , 'checkCode'] );
 Route::post('/resetpassword' ,[ResetPasswordSeekerController::class , 'resetPassword'] );
});
 Route::group(['prefix' => 'seeker', 'middleware' => ['auth:seeker-api' , 'scopes:seeker']] , function(){
    //logout 
    Route::post('/logout' ,[AuthSeekerController::class , 'logout'] );
    Route::post('/edit-profile' , [ProfileController::class, 'editProfile']);
    Route::get('/profile' , [ProfileController::class, 'getProfile']);
    Route::post('/upload' , [ProfileController::class, 'uploadImage']);
    Route::get('/show-image' , [ProfileController::class, 'showImage']);
    Route::post('/add-friend/{id2}/role/{model2}' , [FriendshipController::class, 'addFriend']);
    Route::delete('/remove-friend/{id2}/role/{model2}' , [FriendshipController::class, 'removeFriend']);
    Route::get('/get-friends' , [FriendshipController::class, 'show']);

});