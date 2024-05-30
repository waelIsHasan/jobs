<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Freelancer;
use App\Models\Job;
use App\Models\Save;

use App\Traits\ResponseTrait;

class SaveController extends Controller
{
    use ResponseTrait;

    public function saveJobPost($idJob){
        $id = auth()->id();
        $job = Job::find($idJob);
        if(!$job)
           return $this->failedResponse('I can not find this job' , null);
        $saveRecord = Save::where('freelancer_id',$id)->where('job_id' , $idJob)->first();
       
        if($saveRecord)
            return  $this->failedResponse('has already saved', null);

        Save::create([
            "freelancer_id" => $id,
            'job_id' => $idJob
        ]);

        return $this->successResponse('saved it successfully', null);
    }


    public function unsaveJobPost($idJob){
        $id = auth()->id();
        $job = Job::find($idJob);
        if(!$job)
           return $this->failedResponse('I can not find this job' , null);
        $saveRecord = Save::where('freelancer_id',$id)->where('job_id' , $idJob)->first();
       
        if(!$saveRecord)
            return  $this->failedResponse('you have not had this post before in save box', null);
        
            $saveRecord->delete();
        return $this->successResponse('unsaved it successfully', null);
    }

    public function savedPosts(){
        $id = auth()->id();
        $freelancer = Freelancer::find($id);

        $jobs = [];
        $arrayOfSave = $freelancer->saves;        
        foreach($arrayOfSave as $saveRecord){
            $jobs[] = Job::find($saveRecord->job_id);
        }
        return $this->successResponse('you have '.count($jobs).' saved posts' , $jobs);
    }       
}
