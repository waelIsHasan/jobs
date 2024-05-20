<?php

namespace App\Services;
use App\Notifications\ResetPasswordTokenNotification;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService {

    public function forgotPassword($request , $model ,$passwordResetTokenModel){
       
        $nameOfModel = strtolower(class_basename($model));
        
        $data = $request->validate([
            'email'=>'required|exists:'.$nameOfModel.'s',

        ]);

        $passwordResetTokenModel::where('email' , $request->email)->delete();
        $i = 0;
        $token = '';
        while($i != 6){
            $i++;
            $token .= random_int(0 , 9);
        }
        $data['token']= $token;
        $passwordResetTokenModel::create($data);

        $user = $model::where('email' , $request->email)->first();
        $user ->notify(new ResetPasswordTokenNotification($token));
      
        return [
            'msg' => 'sent otp successfully.check your mail box '
        ];
    }

    public function resetPassword($request , $model , $passwordResetTokenModel){
        $data = $request->validate([
            'token'=>'required',
            'password'=>'required'
        ]);
        $passwordToken = $passwordResetTokenModel::where('token' , $request->token)->first();
        if($passwordToken == null){
            return ['success' => false , 'msg' => 'there is no token'];
        }
        if($passwordToken['created_at']->addMinutes(60) >= now()){
            $seeker = $model::where('email' , $passwordToken['email']);
            $password = $request->password;
            $seeker->update(['password' => Hash::make($password)]); 
            $passwordToken->delete();
            return ['success' => true , 'msg' => 'Reset password successfully'];
        }
        $passwordToken->delete();
        return ['success' => false , 'msg' => 'expire'];
    }

    public function checkCode( $request ,$passwordResetTokenModel){
        $code =  $request->validate([
            'token'=>'required'
        ]);
        $passwordToken = $passwordResetTokenModel::where('token' , $request->token)->first();
        if($passwordToken == null){
            return ['success' => false , 'msg' => 'there is no token'];
        }
        if($passwordToken['created_at']->addMinutes(60) >= now()){
            return ['success' => true , 'msg' => 'vaild token'];  
        }
        return ['success' => false , 'msg' => 'expire'];
    }


}