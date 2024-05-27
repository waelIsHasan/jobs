<?php 
namespace App\Services;
use App\Models\Friendship;
use Illuminate\Support\Str;

class FriendshipService {

    public function addFriend($id , $model , $id2 , $model2){
        if(($id == $id2) && ($model == Str::title($model2))){
            return ["success" => false,"msg" => "you can not be friend of yourself"];
        }
        $existingFriendship1 = Friendship::where(
            'friend_one_able_id' , $id)
            ->where(
            'friend_one_able_type' , $model)
            ->where(
            'friend_two_able_id' , intval($id2))
            ->where(
            'friend_two_able_type' , Str::title($model2))
            ->first();
            
        $existingFriendship2 = Friendship::where(
            'friend_two_able_id' , $id)
            ->where(
            'friend_two_able_type' , $model)
            ->where(
            'friend_one_able_id' , intval($id2))
            ->where(
            'friend_one_able_type' , Str::title($model2))
            ->first();
            
        if ($existingFriendship1 == null &&  $existingFriendship2 == null) {
            $friendship = Friendship::create([
                'friend_one_able_id' => $id,
                'friend_one_able_type' => $model,
                'friend_two_able_id' => intval($id2),
                'friend_two_able_type' => Str::title($model2),
            ]);
            return ["success" => true,"msg" => "you are friends","data" => $friendship];
        } else {
            return ["success" => false,"msg" => "you are already friends"];
        }
    }


    public function removeFriend($id , $model , $id2 , $model2){
     Friendship::where(
            'friend_one_able_id' , $id)
            ->where(
            'friend_one_able_type' , $model)
            ->where(
            'friend_two_able_id' , intval($id2))
            ->where(
            'friend_two_able_type' , Str::title($model2))
            ->delete();
            
       Friendship::where(
            'friend_two_able_id' , $id)
            ->where(
            'friend_two_able_type' , $model)
            ->where(
            'friend_one_able_id' , intval($id2))
            ->where(
            'friend_one_able_type' , Str::title($model2))
            ->delete();
            return ["success" => true,"msg" => "you are not friends now." ];
        
    }



}