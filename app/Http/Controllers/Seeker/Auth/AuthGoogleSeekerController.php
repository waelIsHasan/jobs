<?php

namespace App\Http\Controllers\Seeker\Auth;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\Seeker;
use Illuminate\Support\Facades\Hash;
use App\Traits\ConfigTrait;



class AuthGoogleSeekerController extends Controller
{
    //
    use ConfigTrait;
    public function redirectToGoogleSeeker()
    {
                $this->configGoogleSeeker();
               return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallbackSeeker()
    {
        $this->configGoogleSeeker();  
        $user =Socialite::driver('google')->user();
       $findUser = Seeker::where('google_id' , $user->id)->first();
        if ($findUser) {
            Auth::guard('seeker')->login($findUser);
            $accessToken = $findUser->createToken('GoogleToken' ,['seeker'])->accessToken;
            return response()->json([
                'token' => $accessToken,
            ]);
        } else {
            // Create a new user with Google login
            $newUser = Seeker::create([
                'first_name' => $user->name,
                'last_name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'email_verified_at'=>now(),
                'password' => Hash::make('google'), // Since the user is logging in with Google, no password is needed
            ]);
            // Log in the newly created user
            Auth::guard('seeker')->login($newUser);
            $accessToken = $newUser->createToken('GoogleToken' ,['seeker'])->accessToken;
            return response()->json([
                'token' => $accessToken,
            ]);
        }
       
    }
}
