<?php

namespace App\Services;

//use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use App\Services\CheckService;
use App\Models\Notification as NotificationModel;
use App\Notifications\NewLoginNotification;
use Exception;

class NotificationService
{
    public function send($user, $title, $message , $data , $type)
    {
        
        $serviceAccountPath = base_path('job-and-freelancing-bcb1d3cbae5b.json');
  $firebaseCredential = (new Factory())->withServiceAccount($serviceAccountPath);
  $messaging = $firebaseCredential->createMessaging();
  // Prepare the notification array
  $notification = [
      'title' => $title,
      'body' => $message,
      'sound' => 'default',
  ];
  

  // Create the CloudMessage instance
  $cloudMessage = CloudMessage::withTarget('token', $user['fcm_token'])
      ->withNotification($notification)
      ->withData($data);
    try{  
      $messaging->send($cloudMessage);

      NotificationModel::create([
            'type' => $type,
            'notifiable_type' => class_basename($user),
            'notifiable_id' => $user['id'],
            'data' => json_encode([
                'body' => $data,
                'message' => $message,
                'title' =>$title,
            ]), // The data of the notification
        ]);
        return 1;
    }catch(Exception $e){
        return $e;
    }
  }

  public function notifyFriends($id , $friends , $modelType ,$type){

    foreach($friends as $friend){
        $idFriend1 = $friend->friend_one_able_id;
        $typeFriend1 = $friend->friend_one_able_type;
        $idFriend2 = $friend->friend_two_able_id;
        $typeFriend2 = $friend->friend_two_able_type;
        if($typeFriend1 != $modelType){
            $model = 'App\Models\\'.$typeFriend1;    
            $user = $model::find($idFriend1);
            $data = [
                'type' => 'basic',
                'name' => $user['first_name'] .$user['last_name'],
                'message' => 'New post has been added',
            ];
            $this->send($user, 'New Job Post','New Job post has been added ', $data ,  $type);

        }
        else if($typeFriend2 != $modelType){        
            $model = 'App\Models\\'.$typeFriend2;
            $user = $model::find($idFriend2);
            $data = [
                'type' => 'basic',
                'name' => $user['first_name'] .$user['last_name'],
                'message' => 'New post has been added',
            ];
            $this->send($user, 'New Job Post','New Job post has been added ', $data , $type);

        }
        else if($idFriend1 != $id){

            
            $model = 'App\Models\\'.$typeFriend1;
            $user = $model::find($idFriend1);
            $data = [
                'type' => 'basic',
                'name' => $user['first_name'] .$user['last_name'],
                'message' => 'New post has been added',
            ];
            $this->send($user, 'New Job Post','New Job post has been added ', $data , $type);

        }
        else {

            
            $model = 'App\Models\\'.$typeFriend2;
            $user = $model::find($idFriend2);
            $data = [
                'type' => 'basic',
                'name' => $user['first_name'] .$user['last_name'],
                'message' => 'New post has been added',
            ];
            $this->send($user, 'New Job Post','New Job post has been added ', $data , $type);
        }
    }
  }

  
    

    public function markAsRead($notificationId)//: //bool
    {
        // $notification = auth()->user()->notifications()->findOrFail($notificationId);

        // if(isset($notification)) {
        //     $notification->markAsRead();
        //     return true;
        // }else return false;
    }

    public function destroy($id)//: bool
    {
        // $notification = auth()->user()->notifications()->findOrFail($id);

        // if(isset($notification)) {
        //     $notification->delete();
        //     return true;
        // }else return false;
    }

}
