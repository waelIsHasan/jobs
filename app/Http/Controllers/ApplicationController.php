<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Freelancer;
use App\Models\Job;
use App\Traits\ResponseTrait;
use App\Services\ApplicationService;
use Exception;
class ApplicationController extends Controller
{
    
    use ResponseTrait;

    protected $applicationService;
    public function __construct(ApplicationService $applicationService){
        $this->applicationService  = $applicationService;
    }
    
    public function jobSearsh(Request $request){      
        $searsh = $request->only('title','location','dead_time' ,'salary' , );
        $job = Job::searsh($searsh)->get();
         return $this->successResponse('searsh job' , $job );
         
    }

    public function apply(Request $request , $jobId){
        
        // $id = auth()->id();
        // $response = $this->applicationService->apply($request , $jobId , $id);
        // if($response['success']){
        //     return $this->successResponse($response['msg'] , $response['data']);
        // }
        // else{
        //     return $this->failedResponse($response['msg'] ,null);
        // }
          
        $id = auth()->id(); 
        try{
            $response = $this->applicationService->apply($request , $jobId , $id);   
          
            if($response['success'] ){
                return $this->successResponse('apply Job successfully', $response['application']);
            }   
           else {
                return $this->failedResponse($response['msg'] , null , $response['status']);
            }
        }catch(Exception $e){

            return $this->failedResponse('bad Request' , $e , 400);
        }
    }
    
    public function showApplications(Request $request){

        $id = auth()->id();
        $freelancer = Freelancer::find($id);

        if($freelancer){
            $applications = $freelancer->applications;
            return $this->successResponse('your applications' , $applications );
        }else{
             return $this->failedResponse('you dont have permission', null);
        }

    }

    public function showApplication($appId){

        $id = auth()->id();
        $application = Application::find($appId);
        if($application == null){
            return $this->failedResponse('No Application match your query', null);
        }
        if($application->freelancer_id ==$id){
            return $this->successResponse('your application' , $application );
        }else{
            return $this->failedResponse('you do not have permission', null);
        }
    } 
}