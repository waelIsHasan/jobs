<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\Owner;
use App\Models\Job;
use App\Models\Application;
use App\Services\PostJobService;

class PostJobController extends Controller
{
    use ResponseTrait;
    protected $postJobService;
    public function __construct(PostJobService $postJobService){
        $this->postJobService = $postJobService;
    }

    public function postJob(Request $request)
        {
            $id = auth()->id();
            // $user = auth()->user();
            // $user = Owner::where('id' ,$id)->first();
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
            if($job->owner_id ==$id){
                $job->delete();
               return  response()->json('delete successfully', 200);
            }
            return  response()->json(' job not found', 200);
        }

        public function getJobs(){
            $id = auth()->id();
            $owner = Owner::find($id);
            return $owner->jobs;
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
