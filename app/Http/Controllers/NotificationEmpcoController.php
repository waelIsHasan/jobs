<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Owner;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Models\Notification as NotificationModel;
use App\Notifications\NewLoginNotification;
use Exception;

use Illuminate\Http\Request;

class NotificationEmpcoController extends Controller
{  
  
  public function send(){
  $serviceAccountPath = base_path('job-and-freelancing-bcb1d3cbae5b.json');
  $firebaseCredential = (new Factory())->withServiceAccount($serviceAccountPath);
  $messaging = $firebaseCredential->createMessaging();
  // Prepare the notification array
  $notification = [
      'title' => "hello",
      'body' => "hello",
      'sound' => 'default',
  ];
  // Additional data payload
  $data = [
      'type' => 'basic',
      'id' => 4,
      'message' => 'hello',
  ];

  // Create the CloudMessage instance
  $cloudMessage = CloudMessage::withTarget('token', 'd5WoktU9Sn6ZVuYtCwBH3K:APA91bHerZbtVH67vD1yT3VMEc04fOGAvUYMhBAsfQXV2n97ltM_JTvssu9qF4FYIuiOQvDmAxNC4PNetgJAH4xs5wmbZqVJu8uHCz71lRq87va8fDFFS2_XRWy3NjURWXaDQg1Tnvy9')
      ->withNotification($notification)
      ->withData($data);
    try{  


      $messaging->send($cloudMessage);

      NotificationModel::create([
            'type' => 'notificatoni',
            'notifiable_type' => 'user/model',
            'notifiable_id' => 1,
            'data' => ([
                'body' => 'hello',
                'message' => 'body',
                'title' =>'heoll',
            ]), // The data of the notification
        ]);
      

    }catch(Exception $e){
      return $e;
    }
      return response()->json(['msg' => 'create notification successfully']);
  }
}
