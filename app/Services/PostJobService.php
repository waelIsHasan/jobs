<?php
namespace App\Services;
use App\Models\Job;
use App\Models\Owner;
use Exception;

class PostJobService{

    public function postJob($request , $id ){
     
            $job = Job::create([
                'title' => $request['title'],
                'body' => $request['body'],
                'required_skills' => $request['required_skills'],
                'location' => $request['location'],
                'category_id' => $request['category_id'],
                'dead_time' => $request['dead_time'],
                'owner_id' =>$id,                  
            ]);
            return ['job' => $job];
   
    }

    public function updateJob($request , $id, $job){
     try{
        if($job->owner_id ==$id){
            $job->update([
                'title' => (($request['title'] == null && !($request->has('title'))) ? $job['title'] : $request['title']),
                'body' => (($request['body'] == null && !($request->has('body'))) ? $job['body'] : $request['body']),
                'required_skills' => (($request['required_skills'] == null && !($request->has('required_skills'))) ? $job['required_skills'] : $request['required_skills']),
                'location' => (($request['location'] == null && !($request->has('location'))) ? $job['location'] : $request['location']),
                'category_id' => (($request['category_id'] == null && !($request->has('category_id'))) ? $job['category_id'] : $request['category_id']),
                'dead_time' =>(($request['dead_time'] == null && !($request->has('dead_time'))) ? $job['dead_time'] : $request['dead_time']),
                'owner_id' =>$id,                  
                      ]);   
                      return [
                        'success' => true ,
                        'msg' => 'update your job successfully',
                        'data' => $job
                        ];
                    } 

                    else {
        
                        return ['success' => false,
                        'msg' => 'job is not found' ,
                        'status' => 400];
                    
                    }

                    
                    
    }

                      
                catch(Exception $e){
                return ['success' => false,
                'msg' => 'do not have permssion' ,
                'status' => 400];
                }
            }

}