<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\Freelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\ResponseTrait;
use PhpParser\Builder;
use App\Services\FriendshipService;
class FriendshipController extends Controller
{
    use ResponseTrait;
    protected $friendshipService;
    public function __construct(FriendshipService $friendshipService){
        $this->friendshipService = $friendshipService;
    }
    public function addFriend($id2, $model2)
    {
        $id = auth()->id();
        $user = auth()->user();
        $model = class_basename($user);
        $response = $this->friendshipService->addFriend($id ,$model , $id2 , $model2);
        if($response['success']){
            return $this->successResponse($response['msg'] , $response['data']);
        } else {
            return $this->failedResponse($response['msg'] ,null , 400);
        }
    }

    public function removeFriend($id2, $model2)
    {
        $id = auth()->id();
        $user = auth()->user();
        $model = class_basename($user);
        $response = $this->friendshipService->removeFriend($id ,$model , $id2 , $model2);
            return $this->successResponse($response['msg'],null);
    }

    public function show()
    {
        $user = auth()->user();
        $id = auth()->user()->id;
        $friends = $user->friends;
        $numOfFriends = count($friends);
        $arr = [];
        foreach($friends as $friend){
            if($friend->friend_one_able_id != $id || $friend->friend_one_able_type != class_basename($user)){
                $path = 'App\Models\\'.$friend->friend_one_able_type;
                $name = $path::find($friend->friend_one_able_id)->first_name.' '.$path::find($friend->friend_one_able_id)->last_name;
                $friend['name']=$name; 
                $friend['career'] =$path::find($friend->friend_two_able_id)->profile->work_as;
                $friend['image'] =$path::find($friend->friend_two_able_id)->profile->image;
                $arr[] = $friend;
            }
            else {

                $path = 'App\Models\\'.$friend->friend_two_able_type;
                $name = $path::find($friend->friend_two_able_id)->first_name.' '.$path::find($friend->friend_two_able_id)->last_name;
                $friend['name']=$name; 
                $friend['career'] =$path::find($friend->friend_two_able_id)->profile->work_as;
                $friend['image'] =$path::find($friend->friend_two_able_id)->profile->image;
                $arr[] = $friend;
            }            
        }
        return $this->successResponse('you have '.$numOfFriends.' Friends' ,(count($arr) == 0) ? $friends : $arr);
    }
}
