<?php
namespace App\Services;
use App\Models\Profile;
use Illuminate\Support\Facades\File;
class ProfileService {

    public function editProfile($request , $user , $id , $model){
        if($user->profile == null){
            $profile = Profile::create([
                'Bio' => $request['Bio'],
                'home_place' => $request['home_place'],
                'work_place' => $request['work_place'],
                'birthday' => $request['birthday'],
                'profileable_id' =>$id,
                'profileable_type' => class_basename($model),                    
            ]);
            return ['profile' => $profile];
    }else {
        $profile = $user->profile;
            $profile->update([
                'Bio' => (($request['Bio'] == null && !($request->has('Bio'))) ? $profile['Bio'] : $request['Bio']),
                'home_place' =>(($request['home_place'] == null && !($request->has('home_place'))) ? $profile['home_place'] : $request['home_place']),
                'work_place' => (($request['work_place'] == null && !($request->has('work_place'))) ? $profile['work_place'] : $request['work_place']),
                'birthday' =>(($request['birthday'] == null && !($request->has('birthday'))) ? $profile['birthday'] : $request['birthday']),
                'profileable_id' => $id,
                'profileable_type' => class_basename($model),                    
            ]);
            return ['profile' => $profile];
    }
    }

    public function uploadImage($request , $user , $id , $model){
        // if this user has image ,first I will delete it        
        $nameFolder = strtolower(class_basename($model));
        $image = $request->file('file')->getClientOriginalName();
       // upload to server
        $path = $request->file('file')->storeAs($nameFolder,$image,'empco');
        
        //save it in database
        if($user->profile == null){
            $profile = Profile::create([
                'image' => ("images/".$path),
                'profileable_id' =>$id,
                'profileable_type' => class_basename($model),                    
            ]);
        
            return ['profile' => $profile];
        }else {

            if( File::exists(public_path($user->profile['image']))){
                File::delete(public_path($user->profile['image']));
            }
            $path = $request->file('file')->storeAs($nameFolder,$image,'empco');

            $profile = $user->profile;
                $profile->update([
                    'image' => ("images/".$path),
                    'profileable_id' => $id,
                    'profileable_type' => class_basename($model),                    
                ]);
                return ['profile' => $profile];
        }
    }

}