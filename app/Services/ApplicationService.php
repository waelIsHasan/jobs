<?php
namespace App\Services;

use App\Models\Job;
use App\Models\Owner;
use Exception;
use App\Models\Application;

class ApplicationService
{

    public function apply($request, $jobId, $id)
    {


        $existingJob = Job::find($jobId);

        if ($existingJob == null)
            return ['msg' => 'No job match with your query', 'success' => false];

        $existingApplication = Application::where('freelancer_id', $id)
            ->where('job_id', $jobId)->first();

        if ($existingApplication) {
            return ['msg' => 'You have already applied on this job', 'success' => false];
        }
        $request->validate([
            'resume' => 'required|file',
        ]);

        if ($request->hasFile('resume')) {
            $resume = $request->file('resume')->getClientOriginalName();
            // upload to server

            $path = $request->file('resume')->storeAs('', date('mdYHis') . uniqid() . $resume, 'empco_resume');

        }
        $application = Application::create([
            'resume' => ("resumes/" . $path),
            'freelancer_id' => $id,
            'job_id' => $jobId,
        ]);
        return ['success' => true, 'data' => $application, 'msg' => 'apply successfully'];
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