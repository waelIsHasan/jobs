<?php
namespace App\Services;
use App\Models\Service;
use App\Models\Freelancer;
use Exception;

class PostServiceService{

    public function postService($request , $id ){
     
            $service = Service::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'price' => $request['price'],
                'estimated_time' => $request['estimated_time'],
                'category_id' => $request['category_id'],
                'freelancer_id' =>$id,                  
            ]);
            return ['service' => $service];
   
    }

    public function updateService($request , $id, $service){
     try{
        if($service->freelancer_id ==$id){
            $service->update([
                'name' => (($request['title'] == null && !($request->has('title'))) ? $job['title'] : $request['title']),
                'description' => (($request['body'] == null && !($request->has('body'))) ? $job['body'] : $request['body']),
                'price' => (($request['required_skills'] == null && !($request->has('required_skills'))) ? $job['required_skills'] : $request['required_skills']),
                'estimated_time' => (($request['location'] == null && !($request->has('location'))) ? $job['location'] : $request['location']),
                'category_id' => (($request['category_id'] == null && !($request->has('category_id'))) ? $job['category_id'] : $request['category_id']),
                'freelancer_id' =>$id,                  
                      ]);   
                      return [
                        'success' => true ,
                        'msg' => 'You updat your service',
                        'data' => $service
                        ];
                    } 

                    else {
        
                        return ['success' => false,
                        'msg' => 'service is not found' ,
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