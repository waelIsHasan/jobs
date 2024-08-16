<?php

use App\Http\Controllers\Freelancer\Auth\AuthFreelancerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Freelancer\Auth\ResetPasswordFreelancerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendshipController;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Http\Controllers\SaveController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Freelancer\PostServiceController;
use App\Http\Controllers\Owner\PostJobController;
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
Route::group(['prefix' =>'freelancer' ], function(){
    Route::post('register' ,[AuthFreelancerController::class , 'register']);
    Route::post('login' ,[AuthFreelancerController::class , 'login'] );
    Route::post('verify' ,[AuthFreelancerController::class , 'verifyEmail'] );
    Route::post('/forgetpassword' ,[ResetPasswordFreelancerController::class , 'forgotPassword']);
    Route::post('/check-code' ,[ResetPasswordFreelancerController::class , 'checkCode'] );
    Route::post('/resetpassword' ,[ResetPasswordFreelancerController::class , 'resetPassword'] );
    Route::get('/show/jobs-owner/{ownerId}', [PostJobController::class , 'showJobs_Owner']);
    Route::get('/show-job/{jobId}' , [PostJobController::class, 'showJob']);
    Route::get('/show-job-category/{categoryId}' , [PostJobController::class, 'showJobsByCategory']);
    Route::get('/show-jobs' , [PostJobController::class, 'showJobsbyFreelancer']);


});


Route::group(['prefix' => 'freelancer', 'middleware' => ['auth:freelancer-api' , 'scopes:freelancer']] , function(){
    //logout 
    Route::post('/logout' ,[AuthFreelancerController::class , 'logout'] );
    
    Route::post('/modify-profile' , [ProfileController::class, 'editProfile']);
    Route::get('/profile' , [ProfileController::class, 'getProfile']);
    Route::post('/upload' , [ProfileController::class, 'uploadImage']);
    Route::get('/show-image' , [ProfileController::class, 'showImage']);
    
    
    Route::post('/add-friend/{id2}/role/{model2}' , [FriendshipController::class, 'addFriend']);
    Route::delete('/remove-friend/{id2}/role/{model2}' , [FriendshipController::class, 'removeFriend']);
    Route::get('/get-friends' , [FriendshipController::class, 'show']);
   
    Route::post('/search' , [ApplicationController::class, 'jobSearsh']);
    Route::post('/apply/{jobId}' , [ApplicationController::class, 'apply']);
    Route::get('/applications' , [ApplicationController::class, 'showApplications']);
    Route::get('/application/{appId}' , [ApplicationController::class, 'showApplication']);
    
    
    Route::get('/save-post/{jobId}' , [SaveController::class, 'saveJobPost']);
    Route::get('/unsave-post/{jobId}' , [SaveController::class, 'unsaveJobPost']);
    Route::get('/saved-posts' , [SaveController::class, 'savedPosts']);

    Route::post('/post-service' , [PostServiceController::class, 'postService']);
    Route::put('/update-service/{serviceId}' , [PostServiceController::class, 'updateService']);
    Route::delete('/delete-service/{serviceId}' , [PostServiceController::class, 'deleteService']);
    Route::get('/show-services' , [PostServiceController::class, 'showService']);

    Route::post('/send-message/{receiverId}/role/{receiverType}' , [ChatController::class , 'sendMessage']);
    Route::get('/messages/{receiver-id}/role/{role}' , [ChatController::class , 'getMessages']);

    Route::post('/upload-license' , [PostServiceController::class, 'uploadLicense']);
    Route::get('/check-license' , [PostServiceController::class, 'checkLicense']);

    Route::get('/notifications' , [AdminController::class , 'showNotifications']);


});


Route::group(['middleware' => ['scope:freelancer,owner']] , function(){
    Route::get('/hello' , function(){
        return "hello";
    });
});



