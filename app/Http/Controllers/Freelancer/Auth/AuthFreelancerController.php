<?php

namespace App\Http\Controllers\Freelancer\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Freelancer;
use App\Traits\ResponseTrait;
use App\Http\Requests\RegisterRequest;
use Ichtrojan\Otp\Otp;
use App\Services\BaseAuthService;


class AuthFreelancerController extends Controller
{
    protected $baseAuthService;

    public function __construct(BaseAuthService $baseAuthService)
    {
        $this->baseAuthService = $baseAuthService;
    }
    use ResponseTrait;
    public function register(RegisterRequest $request)
    {
        $response = $this->baseAuthService->register($request ,Freelancer::class );
        if ($response['success']) {
            return $this->successResponse($response['msg'], $response['data']);
        } else {
            return $this->failedResponse($response['msg'], null, $response['status']);
        }
    }

    public function login(Request $request)
    {
        $response = $this->baseAuthService->login($request , Freelancer::class);
        if($response['success']){
            return $this->successResponse($response['msg'] , $response['data']);
        }
        else {
            // unverified
            if($response['status'] == 401){
                return $this->failedResponse($response['msg'] , null , 401);
            }
            else {
                // password or email wrong
                return $this->failedResponse($response['msg'] , null , 400);
            }
        }
    }
    public function logout(Request $request)
    {
        $response = $this->baseAuthService->logout($request);
        return $this->successResponse($response['msg'],null);       
    }

    public function verifyEmail(Request $request){
        $otp = new Otp();
        $vaildator = $otp->validate($request->email , $request->token);
        if(!$vaildator->status){
           return $this->failedResponse($vaildator->message ,null ,400); 
        }
        $freelancer = Freelancer::where('email' , $request->email)->first();
        $freelancer->update(['email_verified_at'=> now()]);
        return $this->successResponse('verified succssfully',null );
    }
}
