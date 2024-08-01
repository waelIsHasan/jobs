<?php

use App\Http\Controllers\Owner\Auth\AuthOwnerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\Auth\ResetPasswordOwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Owner\PostJobController;
use App\Http\Controllers\FriendshipController;

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
Route::group(['prefix' =>'owner' ], function(){
    Route::post('register' ,[AuthOwnerController::class , 'register']);
    Route::post('login' ,[AuthOwnerController::class , 'login'] );
    Route::post('verify' ,[AuthOwnerController::class , 'verifyEmail'] );
    Route::post('forgetpassword' ,[ResetPasswordOwnerController::class , 'forgotPassword']);
    Route::post('check-code' ,[ResetPasswordOwnerController::class , 'checkCode'] );
    Route::post('resetpassword' ,[ResetPasswordOwnerController::class , 'resetPassword'] );
    Route::get('/show-job/{jobId}' , [PostJobController::class, 'showJob']);

});
Route::group(['prefix' => 'owner', 'middleware' => ['auth:owner-api' , 'scopes:owner']] , function(){
    //logout 
    Route::post('/logout' ,[AuthOwnerController::class , 'logout'] );
    //profile
    Route::post('/modify-profile' , [ProfileController::class, 'editProfile']);
    Route::get('/profile' , [ProfileController::class, 'getProfile']);
    Route::post('/upload' , [ProfileController::class, 'uploadImage']);
    Route::get('/show-image' , [ProfileController::class, 'showImage']);

    //jobs
    Route::post('/post-job' , [PostJobController::class, 'postJob']);
    Route::put('/update-job/{jobId}' , [PostJobController::class, 'updateJob']);
    Route::delete('/delete-job/{jobId}' , [PostJobController::class, 'deleteJob']);
    Route::get('/show-jobs' , [PostJobController::class, 'showJobs']);


    Route::get('/application/{jobId}' , [PostJobController::class, 'showApplication']);
    Route::get('/approve-application/{appId}' , [PostJobController::class, 'approveApplication']);
    Route::get('/reject-application/{appId}' , [PostJobController::class, 'rejectApplication']);
    Route::get('/applications' , [PostJobController::class , 'showApplicationsByOwner']);

    //firendship
    Route::post('/add-friend/{id2}/role/{model2}' , [FriendshipController::class, 'addFriend']);
    Route::delete('/remove-friend/{id2}/role/{model2}' , [FriendshipController::class, 'removeFriend']);
    Route::get('/get-friends' , [FriendshipController::class, 'show']);

    Route::post('/send-message/{receiverId}/role/{receiverType}' , [ChatController::class , 'sendMessage']);
    Route::get('/messages/{receiverId}/role/{receiverType}' , [ChatController::class , 'getMessages']);

});

