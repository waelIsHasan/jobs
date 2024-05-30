<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\Owner;
use App\Models\Job;
use App\Models\Application;
use App\Services\PostJobService;
use Exception;

use App\Http\Requests\PostJobRequest;

class PostJobController extends Controller
{
    use ResponseTrait;
    protected $postJobService;
    public function __construct(PostJobService $postJobService){
        $this->postJobService = $postJobService;
    }

    public function postJob(PostJobRequest $request)
        {
            $id = auth()->id();
            $response = $this->postJobService->postJob($request  , $id);
            return $this->successResponse('post Job successfully' , $response['job'] );
          
        }   
        public function updateJob(Request $request,$jobId){
            $id = auth()->id();
            $job = Job::find($jobId);
            
            $response = $this->postJobService->updateJob($request,$id,$job);
            if ($response['success']) {
                return $this->successResponse($response['msg'], $response['data']);
            } else {
                return $this->failedResponse($response['msg'], null, $response['status']);
            }   
            
            
        }

        public function deleteJob($jobId){
            $id = auth()->id();
            $job = Job::find($jobId);
            if($job == null){
                return $this->failedResponse('job not found !' ,null);

            }

        try{
            if($job->owner_id == $id){
                $job->delete();
               return $this->successResponse('delete job successfully' ,null );
            }
        }
        catch(Exception $e){
            return $this->failedResponse($e ,null);
        }
        }

        public function showJobs(){
            $id = auth()->id();
            $owner = Owner::find($id);
            return  $this->successResponse('you have '.count($owner->jobs).' job posts' ,$owner->jobs);;
        }

        public function showApplication($jobId){
            $id = auth()->id();
           $job = Job::find($jobId);

        if($job){
            $applications = $job->applications;
            return $this->successResponse('applications', $applications);
        }else{
            return $this->failedResponse('job not found', null);
        }

        }

        public function approveApplication($appId){
            $id = auth()->id();
            $user = auth()->user();

            $application = Application::find($appId);
            if($application == null){
                return $this->failedResponse('I can not find the appliction' , null);
            }

            $job = Job::find($application->job_id);

            if($id == $job->owner_id){
            $application->status = 'approved';
            $application->save();
            return $this->successResponse('application approved successfully',null);
            }else{
                return $this->failedResponse('you not have permssion', null);
            }

        }


        public function rejectApplication($appId){
            $id = auth()->id();
            $user = auth()->user();
            $application = Application::find($appId);

            if($application == null){
                return $this->failedResponse('I can not find the appliction' , null);
            }


            $job = Job::find($application->job_id);
            if($id == $job->owner_id){
            $application->status = 'rejected';
            $application->save();
            return $this->successResponse('application rejected successfully',null);
            }else{
                return $this->failedResponse('you not have permssion', null);
            }
    
        }
}
