<?php
namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifiedEmailMail;
use App\Services\NotificationService;
use App\Models\Admin;
use Exception;

class BaseAuthService
{
    protected $notificationService;
    public function __construct(NotificationService $notificationService){
        $this->notificationService = $notificationService;
    }

    public function register($request, $model)
    {
        //chech if the user in the system
        $chechEmail = $model::where('email', 'like', "%" . $request->email . "%")->get();
        if (count($chechEmail) != 0) {
            return [
                'success' => false,
                'msg' => "The current email has registerd already",
                'status' => 400
            ];
        }
        //send otp 
        Mail::to($request->email)->send(new VerifiedEmailMail($request->email));
        //create new user
        $input = $request->all();
        $input['password'] = Hash::make($request['password']);
        $user = $model::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            "password" => $input['password'],
            'fcm_token' => $input['fcm_token'] != null ? $input['fcm_token'] : null,
            "email" => $input['email']
        ]);
        //create token
        $success['token'] = $user->createToken('tokenfor' . class_basename($model), [strtolower(class_basename($model))])->accessToken;
       //notification for fcm 
        try{
            $admin = Admin::find(1);
            $data = [
                'type' => 'basic',
                'name' => $user['first_name'] .$user['last_name'],
                'message' => 'New User have Register In empco',
            ];
         //   $this->notificationService->send($admin , 'New Register' , 'New User have Register In empco' , $data , 'Registeration');
        
        }catch(Exception $e){
            return ['success' => false , 'msg' => $e];
        }
        return [
            'success' => true,
            'msg' => 'You have registered successfully. Please check your email inbox to verify the email',
            'data' => $success,
        ];
       
        
    }

    public function login($request, $model)
    {
        //name of model in lower case
        $nameOfModel = strtolower(class_basename($model));
        // check
        if (auth()->guard($nameOfModel)->attempt(['email' => $request->email, 'password' => $request->password])) {
            config(['auth.guards.api.provider' => $nameOfModel]);
            $user = $model::query()->select($nameOfModel . 's.*')->find(auth()->guard($nameOfModel)->user()->id);

            if ($user->email_verified_at == null)
                return [
                    'success' => false,
                    'msg' => 'verify your email first',
                    'status' => 401,
                ];

            $data['token'] = $user->createToken('tokenfor' . class_basename($model), [$nameOfModel])->accessToken;
            $user['fcm_token'] = $request['fcm_token'];
            $user->save();
            return [
                'success' => true,
                'msg' => 'You have Logged in successfully',
                'data' => $data
            ];
        } else {

            return [
                'success' => false,
                'msg' => 'password or email is not right',
                'status' => 400
            ];

        }
    }
    
    public function loginOfAdmin($request, $model)
    {
        //name of model in lower case
        $nameOfModel = strtolower(class_basename($model));
        // check
        if (auth()->guard($nameOfModel)->attempt(['email' => $request->email, 'password' => $request->password])) {
            config(['auth.guards.api.provider' => $nameOfModel]);
            $user = $model::query()->select($nameOfModel . 's.*')->find(auth()->guard($nameOfModel)->user()->id);

            $data['token'] = $user->createToken('tokenfor' . class_basename($model), [$nameOfModel])->accessToken;
            $user['fcm_token'] = $request['fcm_token'];
            $user->save();
            return [
                'success' => true,
                'msg' => 'You have Logged in successfully',
                'data' => $data
            ];
        } else {

            return [
                'success' => false,
                'msg' => 'password or email is not right',
                'status' => 400
            ];

        }
    }


    public function logout($request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return ['msg' => 'logged out successfully'];
    }



}