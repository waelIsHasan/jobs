<?php
namespace App\Services;
use App\Models\Profile;
use Illuminate\Support\Facades\File;
use App\Traits\HelperTrait;
class ProfileService {
    use HelperTrait;
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
                'Bio' => $this->dynamicCheck($request , $profile , 'Bio'),
                'home_place' =>$this->dynamicCheck($request , $profile , 'home_place'),
                'work_place' =>$this->dynamicCheck($request , $profile , 'work_place'),
                'birthday' =>$this->dynamicCheck($request , $profile , 'birthday'),
                'profileable_id' => $id,
                'profileable_type' => class_basename($model),                    
            ]);
            return ['profile' => $profile];
    }
    }

    public function uploadImage($request , $user , $id , $model){
       

        // if this user has image ,first I will delete it        
        $nameFolder = strtolower(class_basename($model));
        $image = $request->file('image')->getClientOriginalName();

       // upload to server
        $path = $request->file('image')->storeAs($nameFolder, date('mdYHis') . uniqid() .$image,'empco');
        
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
              File::delete(public_path($user->profile['image']));;
            }
            $path = $request->file('image')->storeAs($nameFolder,date('mdYHis') . uniqid() .$image,'empco');

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