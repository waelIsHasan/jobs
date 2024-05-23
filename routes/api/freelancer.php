<?php

use App\Http\Controllers\Freelancer\Auth\AuthFreelancerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\Auth\ResetPasswordFreelancerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendshipController;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Http\Controllers\Freelancer\ApplicationJobController;

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
Route::group(['prefix' =>'freelancer' ], function(){
    Route::post('register' ,[AuthFreelancerController::class , 'register']);
    Route::post('login' ,[AuthFreelancerController::class , 'login'] );
    Route::post('verify' ,[AuthFreelancerController::class , 'verifyEmail'] );
    Route::post('/forgetpassword' ,[ResetPasswordFreelancerController::class , 'forgotPassword']);
    Route::post('/check-code' ,[ResetPasswordFreelancerController::class , 'checkCode'] );
    Route::post('/resetpassword' ,[ResetPasswordFreelancerController::class , 'resetPassword'] );
});


Route::group(['prefix' => 'freelancer', 'middleware' => ['auth:freelancer-api' , 'scopes:freelancer']] , function(){
    //logout 
    Route::post('/logout' ,[AuthFreelancerController::class , 'logout'] );
    Route::post('/edit-profile' , [ProfileController::class, 'editProfile']);
    Route::get('/profile' , [ProfileController::class, 'getProfile']);
    Route::post('/upload' , [ProfileController::class, 'uploadImage']);
    Route::get('/show-image' , [ProfileController::class, 'showImage']);
    Route::post('/add-friend/{id2}/role/{model2}' , [FriendshipController::class, 'addFriend']);
    Route::delete('/remove-friend/{id2}/role/{model2}' , [FriendshipController::class, 'removeFriend']);
    Route::get('/get-friends' , [FriendshipController::class, 'show']);
});



