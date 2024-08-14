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
use App\Models\Category;
use App\Models\CompanyLicense;
use App\Http\Requests\PostJobRequest;
use App\Services\ApplicationService;
class PostJobController extends Controller
{
    use ResponseTrait;
    protected $postJobService;
    protected $applicationService;

    public function __construct(PostJobService $postJobService  , ApplicationService $applicationService)
    {
        $this->postJobService = $postJobService;
        $this->applicationService =$applicationService; 
    }

    public function postJob(PostJobRequest $request)
    {
       try {
            
            $id = auth()->id();
          
            $response = $this->postJobService->postJob($request, $id);
   
            if($response['success']){
                return $this->successResponse('post Job successfully', $response['job']);
            }
                   
           elseif($response['status'] == 401){
                return $this->failedResponse($response['msg'] , null , 401);
            }
            elseif($response['status'] == 404){
                return $this->failedResponse($response['msg'] , null , 404);
            }
        } catch (Exception $e) {
            return $this->failedResponse('You have to enter all attributes', $e, 400);

        }
    }
    public function updateJob(Request $request, $jobId)
    {
        $id = auth()->id();
        $job = Job::find($jobId);

        $response = $this->postJobService->updateJob($request, $id, $job);
        if ($response['success']) {
            return $this->successResponse($response['msg'], $response['data']);
        } else {
            return $this->failedResponse($response['msg'], null, $response['status']);
        }


    }

    public function deleteJob($jobId)
    {
        $id = auth()->id();
        $response = $this->postJobService->deleteJob($jobId , $id);
        if ($response['success']) {
            return $this->successResponse($response['msg'],null);
        } else {
            return $this->failedResponse($response['msg'], null);
        }
    }


    public function showJob($jobId)
    {
        $job = Job::find($jobId);
        if ($job == null) {
            return $this->failedResponse('No job match your query!', null);
        }
        try {
            return $this->successResponse('show job successfully', $job);
        } catch (Exception $e) {
            return $this->failedResponse($e, null);
        }
    }

    public function showJobs()
    {
        $id = auth()->id();
        $owner = Owner::find($id);
        return $this->successResponse (count($owner->jobs) . ' job posts', $owner->jobs);
        ;
    }

    public function showJobsbyFreelancer()
    {
        $jobs = Job::all();
        return $this->successResponse('' . count($jobs) . ' job posts', $jobs);
        ;
    }

    public function showJobs_Owner($id)
    {
        try{
        $owner = Owner::find($id);
        $jobs = $owner->jobs;
        return $this->successResponse( count($jobs) . ' job posts', $jobs);
        ;
        }catch(Exception $e){
            return $this->failedResponse('No Company match your query', null);
        }
    }
    

    public function showApplication($jobId)
    {
        $job = Job::find($jobId);

        if ($job) {
            $applications = $job->applications;
            return $this->successResponse('applications', $applications);
        } else {
            return $this->failedResponse('job not found', null);
        }

    }
    public function showJobsByCategory($categoryId){

        $category = Category::find($categoryId);
        if($category){
            $jobs = $category->jobs;
            return $this->successResponse( count($jobs) . ' job posts', $jobs);
        }
        else{
            return $this->failedResponse("No Category match your query" , null);
        }
    }

    public function approveApplication($appId)
    {
        $id = auth()->id();
        $response = $this->applicationService->approveApplication($appId , $id);
            if($response['success']){
                return $this->successResponse($response['msg'] , null);
            }
            else{
                return $this->failedResponse($response['msg'] , null);
            }
    }


    public function rejectApplication($appId)
    {
        $id = auth()->id();
        $response = $this->applicationService->rejectApplication($appId , $id);
            if($response['success']){
                return $this->successResponse($response['msg'] , null);
            }
            else{
                return $this->failedResponse($response['msg'] , null);
            }
    }

    public function showApplicationsByOwner()
    {
        $user = auth()->user();
        $jobs = $user->jobs;
        $arr = [];
        foreach ($jobs as $job) {
            foreach ($job->applications as $application) {
                $arr[] = $application;
            }
        }
        return $this->successResponse('you have ' . count($arr) . ' applications', $arr);
    }

    public function uploadLicense(Request $request){
        
        $id = auth()->id();
        $response = $this->postJobService->uploadLicense($request , $id);
        if($response['success']){
            return $this->successResponse($response['msg'] , $response['data']);
        }
        elseif($response['status'] == 400){
            return $this->failedResponse($response['msg'] , null , 400);
        }
       
    }

    public function checklicense(Request $request){
        $id = auth()->id();
        $company=CompanyLicense::where('owner_id',$id)->get();
        if($company->isEmpty()){
            return $this->failedResponse('you dont have license' , null , 401);
           
        }else{
            return $this->successResponse('status your license',$company);
        }
    }

}
