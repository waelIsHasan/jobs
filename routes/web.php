<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\Auth\AuthGoogleOwnerController;
use App\Http\Controllers\Seeker\Auth\AuthGoogleSeekerController;
use App\Http\Controllers\Freelancer\Auth\AuthGoogleFreelancerController;
use App\Http\Controllers\NotificationEmpcoController;
use App\Models\NotificationEmpco;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect/seeker', [AuthGoogleSeekerController::class , 'redirectToGoogleSeeker']);
Route::get('/auth/seeker/google/callback', [AuthGoogleSeekerController::class , 'handleGoogleCallbackSeeker']);

Route::get('/auth/redirect/owner', [AuthGoogleOwnerController::class , 'redirectToGoogleOwner']);
Route::get('/auth/owner/google/callback', [AuthGoogleOwnerController::class , 'handleGoogleCallbackOwner']);

Route::get('/auth/redirect/freelancer', [AuthGoogleFreelancerController::class , 'redirectToGoogleFreelancer']);
Route::get('/auth/freelancer/google/callback', [AuthGoogleFreelancerController::class , 'handleGoogleCallbackFreelancer']);

Route::get('/dash' ,function(){
    return view('testMessageRealtime');
});

Route::get('/send' , [NotificationEmpcoController::class , 'send']);