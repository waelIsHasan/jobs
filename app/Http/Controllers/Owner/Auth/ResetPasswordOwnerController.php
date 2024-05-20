<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\PasswordResetToken;
use App\Traits\ResponseTrait;
use App\Services\ResetPasswordService;
class ResetPasswordOwnerController extends Controller
{
    use ResponseTrait;
    protected $resetPasswordService;
    public function __construct(ResetPasswordService $resetPasswordService){
        $this->resetPasswordService = $resetPasswordService;
    }
    public function forgotPassword(Request $request){
        $response = $this->resetPasswordService->forgotPassword($request , Owner::class , PasswordResetToken::class);        
        return $this->successResponse($response['msg'] ,null );
        
    }
    public function checkCode(Request $request){
        $response = $this->resetPasswordService->checkCode($request , PasswordResetToken::class);
        if($response['success']){
            return $this->successResponse($response['msg'] ,null );

        }
        else {
            return $this->failedResponse($response['msg'] ,null , 400);
        }
    }

    public function resetPassword(Request $request){
        $response = $this->resetPasswordService->resetPassword($request , Owner::class , PasswordResetToken::class);
        if($response['success']){
            return $this->successResponse($response['msg'],null);
        }
        else {
            return $this->failedResponse($response['msg'] , null );
        }
    }

}
