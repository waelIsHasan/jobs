<?php
namespace App\Services;
use App\Models\Job;
use App\Models\Owner;

class PostJobService{

    public function postJob($request , $id ){
     
            $job = Job::create([
                'title' => $request['title'],
                'body' => $request['body'],
                'required_skills' => $request['required_skills'],
                'location' => $request['location'],
                'dead_time' => $request['dead_time'],
                'owner_id' =>$id,                  
            ]);
            return ['job' => $job];
   
    }

    public function updateJob($request , $id,$job){
        
        if($job->owner_id ==$id){
            $job->update([
                'title' => $request['title'],
                'body' => $request['body'],
                'required_skills' => $request['required_skills'],
                'location' => $request['location'],
                'dead_time' => $request['dead_time'],
                'owner_id' =>$id,                  
                      ]);   
                      return [
                        'success' => true ,
                        'msg' => 'You updat your job',
                        'data' => $job
                        ];
                    } 
                    else {
        
                        return ['success' => false,
                        'msg' => 'job is not found' ,
                        'status' => 400];
                    
                    }

                    
                    
    }
}