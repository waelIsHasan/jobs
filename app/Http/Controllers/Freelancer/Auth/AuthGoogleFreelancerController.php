<?php

namespace App\Http\Controllers\Freelancer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Freelancer;
use Illuminate\Support\Facades\Hash;
use App\Traits\ConfigTrait;

class AuthGoogleFreelancerController extends Controller
{
    use ConfigTrait;
    
    public function redirectToGoogleFreelancer()
    {
       $this->configGoogleFreelancer();
            return Socialite::driver('google')->redirect();


    }
    public function handleGoogleCallbackFreelancer()
    {
       $this->configGoogleFreelancer();
        $user =Socialite::driver('google')->user(); 
        $findUser = Freelancer::where('google_id' , $user->id)->first();
        
        if ($findUser) {
            Auth::guard('freelancer')->login($findUser);
            $accessToken = $findUser->createToken('GoogleToken' ,['freelancer'])->accessToken;

            return response()->json([
                'token' => $accessToken,
            ]);
        } else {
            // Create a new user with Google login
            $newUser = Freelancer::create([
                'first_name' => $user->name,
                'last_name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'email_verified_at'=>now(),
                'password' => Hash::make('google'), // Since the user is logging in with Google, no password is needed
            ]);
            // Log in the newly created user
            Auth::guard('freelancer')->login($newUser);
            $accessToken = $newUser->createToken('GoogleToken' ,['freelancer'])->accessToken;
            return response()->json([
                'token' => $accessToken,
            ]);
        }
       
    }

}
