<?php
namespace App\Services;

use App\Models\Job;
use App\Models\Owner;
use Exception;
use App\Traits\HelperTrait;

class PostJobService
{
    use HelperTrait;

    public function postJob($request, $id)
    {

        $job = Job::create([
            'title' => $request['title'],
            'body' => $request['body'],
            'required_skills' => $request['required_skills'],
            'location' => $request['location'],
            'category_id' => $request['category_id'],
            'dead_time' => $request['dead_time'],
            'salary' => $request['salary'],
            'type_job' => $request['type_job'],
            'owner_id' => $id,
        ]);
        return ['job' => $job];

    }

    public function updateJob($request, $id, $job)
    {
        try {
            if ($job->owner_id == $id) {
                $job->update([
                    'title' => $this->dynamicCheck($request , $job , 'title'),
                    'body' =>  $this->dynamicCheck($request , $job , 'body'),
                    'required_skills' =>$this->dynamicCheck($request , $job , 'required_skills'),
                    'location' => $this->dynamicCheck($request , $job , 'location'),
                    'category_id' =>  $this->dynamicCheck($request , $job , 'category_id'),
                    'dead_time' =>$this->dynamicCheck($request , $job , 'dead_time'),
                    'salary' => $this->dynamicCheck($request , $job , 'salary'),
                    'type_job' => $this->dynamicCheck($request , $job , 'type_job'),
                    'owner_id' => $id,
                ]);
                return [
                    'success' => true,
                    'msg' => 'update your job successfully',
                    'data' => $job
                ];
            } else {

                return [
                    'success' => false,
                    'msg' => 'do not have permission',
                    'status' => 403
                ];

            }



        } catch (Exception $e) {
            return [
                'success' => false,
                'msg' => 'No Job match your query',
                'status' => 400
            ];
        }
    }


    public function deleteJob($jobId, $id)
    {
        $job = Job::find($jobId);

        if ($job == null) {
            return ['success' => false, 'msg' => 'No job match your query!'];

        }
        try {
            if ($job->owner_id == $id) {
                $job->delete();
                return ['success' => true, 'msg' => 'delete job successfully'];
            } else {
                return ['msg' => 'you do not have permission', 'success' => false];
            }
        } catch (Exception $e) {
            return ['msg' => $e, 'success' => false];
        }
    }


}