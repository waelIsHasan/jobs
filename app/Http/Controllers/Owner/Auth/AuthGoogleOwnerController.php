<?php

namespace App\Http\Controllers\Owner\Auth;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;
use App\Traits\ConfigTrait;

class AuthGoogleOwnerController extends Controller
{
    use ConfigTrait;
    
    public function redirectToGoogleOwner()
    {
       $this->configGoogleOwner();
            return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallbackOwner()
    {

       $this->configGoogleOwner();
        $user =Socialite::driver('google')->user();
        $findUser = Owner::where('google_id' , $user->id)->first();
        
        if ($findUser) {
            Auth::guard('owner')->login($findUser);
            $accessToken = $findUser->createToken('GoogleToken' ,['owner'])->accessToken;

            return response()->json([
                'token' => $accessToken,
            ]);
        } else {
            // Create a new user with Google login
            $newUser = Owner::create([
                'first_name' => $user->name,
                'last_name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
                'email_verified_at'=>now(),
                'password' => Hash::make('google'), // Since the user is logging in with Google, no password is needed
            ]);
            // Log in the newly created user
            Auth::guard('owner')->login($newUser);
            $accessToken = $newUser->createToken('GoogleToken' ,['owner'])->accessToken;
            return response()->json([
                'token' => $accessToken,
            ]);
        }
       
    }
}
