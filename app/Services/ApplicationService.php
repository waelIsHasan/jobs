<?php
namespace App\Services;

use App\Models\Job;
use App\Models\Owner;
use App\Models\EmpplyeLicense;
use Exception;
use App\Models\Application;

class ApplicationService
{

    public function apply($request, $jobId, $id)
    {


        $existingJob = Job::find($jobId);

        if ($existingJob == null)
            return ['msg' => 'No job match with your query', 'success' => false , 'status' => 400];

            $freelancer=EmpplyeLicense::where('freelancer_id',$id)->get();
            if($freelancer->isEmpty()){
                return [
                    'success' => false,
                    'msg' => ' you dont have license ',
                    'status' => 404];
            }else{

            $freelancerLicense = $freelancer->where('status','approved')->first();
        
             if($freelancerLicense){
        $existingApplication = Application::where('freelancer_id', $id)
            ->where('job_id', $jobId)->first();

        if ($existingApplication) {
            return [
                'success' => false,
                'msg' => ' You have already applied on this job ',
                'status' => 403];
          
        }
        $request->validate([
            'resume' => 'required|file',
            'name'=>'required',
            'email'=>'required'
        ]);
            $resume = $request->file('resume')->getClientOriginalName();
            // upload to server
            $path = $request->file('resume')->storeAs('', date('mdYHis') . uniqid() . $resume, 'empco_resume');
     
            $application = Application::create([
            'resume' => ("resumes/".$path),
            'freelancer_id' => $id,
            'name' =>$request['name'],
            'email' => $request['email'],
            'job_id' => $jobId,
            
        ]);
        return [
            'success' => true,
            'msg' => 'You applay job successfully',
            'application' => $application,
            'status'=>200,
            'job' => $existingJob
        ];
        }else{
            return [
                'success' => false,
                'msg' => 'check your license first',
                'status' => 401];
        }
    }
    }

    public function approveApplication($appId, $id)
    {

        $application = Application::find($appId);
        if ($application == null) {
            return ['success' => false, 'msg' => 'No Application match your query'];
        }

        $job = Job::find($application->job_id);

        if ($id == $job->owner_id) {
            $application->status = 'approved';
            $application->save();
            return ['success' => true, 'msg' => 'application approved successfully'];

        } else {
            return ['success' => false, 'msg' => 'you not have permission'];

        }

    }

    public function rejectApplication($appId, $id)
    {
        $application = Application::find($appId);

        if ($application == null) {
            return ['success' => false, 'msg' => 'No Application match your query'];
        }
        $job = Job::find($application->job_id);
        if ($id == $job->owner_id) {
            $application->status = 'rejected';
            $application->save();
            return ['success' => true, 'msg' => 'application rejected successfully'];
        } else {
            return ['success' => false, 'msg' => 'you not have permission'];
        }

    }




}