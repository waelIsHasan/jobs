<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Freelancer;
use App\Models\Job;
use App\Traits\ResponseTrait;

class ApplicationController extends Controller
{
    
    use ResponseTrait;
    public function apply(Request $request , $jobId){
        
        $id = auth()->id();

        $existingJob = Job::find($jobId);
        
        if($existingJob == null)
        return $this->failedResponse('I can not find this job', null);

        $existingApplication = Application::where('freelancer_id',$id)
        ->where('job_id',$jobId)->first();
     
        if($existingApplication){
            return $this->failedResponse('you have already this application', null);
        }
        $request->validate([
            'resume' => 'required|file',
        ]);

        if ($request->hasFile('resume')){
            $resume = $request->file('resume')->getClientOriginalName();
            // upload to server
             $path = $request->file('resume')->storeAs('',$resume,'empco_resume');
        
            }

        $application = Application::create([
            'resume' => ("resumes/".$path),
           // 'cover_later' => $request->cover_later,
            'freelancer_id' =>$id,
            'job_id'=>$jobId,                 
        ]);
        return $this->successResponse('send application' , $application );
    }
    public function showApplications(Request $request){

        $id = auth()->id();
        $freelancer = Freelancer::find($id);

        if($freelancer){
            $applications = $freelancer->applications;
            return $this->successResponse('your applications' , $applications );
        }else{
             return $this->failedResponse('you dont have permssion', null);
        }

    }

    public function showApplication($appId){

        $id = auth()->id();
        $application = Application::find($appId);
        if($application == null){
            return $this->failedResponse('I can not find this application', null);
        }
        if($application->freelancer_id ==$id){
            return $this->successResponse('your application' , $application );
        }else{
            return $this->failedResponse('you do not have permmsion', null);
        }
    }           
}
