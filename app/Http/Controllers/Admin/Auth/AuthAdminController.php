<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Traits\ResponseTrait;
use App\Services\BaseAuthService;

class AuthAdminController extends Controller
{
    protected $baseAuthService;
    use ResponseTrait;

    public function __construct(BaseAuthService $baseAuthService)
    {
        $this->baseAuthService = $baseAuthService;
    }
    public function login(Request $request)
    {
        $response = $this->baseAuthService->loginOfAdmin($request , Admin::class);
        if($response['success']){
            return $this->successResponse($response['msg'] , $response['data']);
        }
        
            else {
                // password or email wrong
                return $this->failedResponse($response['msg'] , null , 400);
            }
        }
    
}
