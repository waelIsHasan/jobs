<?php

namespace App\Http\Controllers;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Services\CheckService;
use  App\Models\Profile;
use App\Services\ProfileService;

class ProfileController extends Controller
{   
    use ResponseTrait;
    protected $checkService;
    protected $profileService;
    public function __construct(CheckService $checkService , ProfileService $profileService){
        $this->checkService = $checkService;
        $this->profileService = $profileService;
    }

    public function editProfile(Request $request)
        {
            $id = auth()->id();
            $user = auth()->user();
            $model = $this->checkService->check($user);
            $user = $model::where('id' ,$id)->first();
            $response = $this->profileService->editProfile($request ,$user , $id ,$model);
            return $this->successResponse('edited profile successfully' , $response['profile'] );
          
        }   

        public function getProfile(){
            $id = auth()->id();
            $user = auth()->user();
            $model = $this->checkService->check($user);
            $user = $model::where('id' ,$id)->first();
            if($user->profile == null){
                return response()->json(['msg' => 'create a new profile']);
            }
            else {
            $profile = $user->profile;
            return response()->json(['data' => $profile]);
            }
        }

        public function uploadImage(Request $request){
            $id = auth()->id();
            $user = auth()->user();
            $model = $this->checkService->check($user);
            $user = $model::where('id' ,$id)->first();
            $response = $this->profileService->uploadImage($request ,$user , $id ,$model);
            return $this->successResponse('upload Image successfully' , $response['profile'] );
        }
        public function showImage(){
            $id = auth()->id();
            $user = auth()->user();
            $model = $this->checkService->check($user);
            $user = $model::where('id' ,$id)->first();
            if($user->profile == null){
                return $this->failedResponse('create a new profile',null , 400);
            }
            $response['image'] = $user->profile['image'];
            return $this->successResponse('', $response);
        }

}
