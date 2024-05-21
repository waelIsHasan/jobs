<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\Owner;
use App\Models\Job;
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

}
