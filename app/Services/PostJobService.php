<?php
namespace App\Services;

use App\Models\Job;
use App\Models\Owner;
use App\Models\CompanyLicense;
use Exception;
use App\Traits\HelperTrait;
use App\Services\NotificationService;
use App\Models\Admin;
class PostJobService
{
    use HelperTrait;

    protected $notificationService;
    public function __construct(NotificationService $notificationService){
        $this->notificationService = $notificationService;
    }

    public function postJob($request, $id)
    {
        $company=CompanyLicense::where('owner_id',$id)->get();
        if($company->isEmpty()){
            return [
                'success' => false,
                'msg' => ' you dont have license ',
                'status' => 404];
        }else{
        $companyLicense = $company->where('status','approved')->first();
        
        if($companyLicense){
        $job = Job::create([
            'title' => $request['title'],
            'body' => $request['body'],
            'required_skills' => $request['required_skills'],
            'location' => $request['location'],
            'category_id' => $request['category_id'],
            'work_nature' =>$request['work_nature'],
            'dead_time' => $request['dead_time'],
            'salary' => $request['salary'],
            'type_job' => $request['type_job'],
            'owner_id' => $id,
        ]);
        $id = auth()->user()->id;
        $owner = "App\Models\Owner";
        $friends = $owner::find($id)->friends;
      
        // notify fcm 
        
        $this->notificationService->notifyFriends($id , $friends , 'Owner','PostingJob');

        return [
            'success' => true,
            'msg' => 'You post job successfully',
            'job' => $job
        ];
        }else{
            return [
                'success' => false,
                'msg' => 'check your license first',
                'status' => 401];
        }
    }
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
                    'work_nature' =>  $this->dynamicCheck($request , $job , 'work_nature'),
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


    public function uploadLicense($request)
    {   
        $id = auth()->user()->id;
        $companyLicense=CompanyLicense::where('owner_id',$id)->first();
        $company = Owner::find($id);
        if($companyLicense){
            return [
                'success' => false,
                'msg' => 'you have license alredy',
                'status' => 400];
        }else{
        $request->validate([
            'license_file' => 'required|file',
        ]);

        if ($request->hasFile('license_file')) {
            $license_file = $request->file('license_file')->getClientOriginalName();
            // upload to server

            $path = $request->file('license_file')->storeAs('ownerLicenses', date('mdYHis') . uniqid() . $license_file, 'empco_resume');

        }
        $companyLicense = CompanyLicense::create([
            'license_file' => ("resumes/" . $path),
            'owner_id' => $id,
        ]);
        try{
            $admin = Admin::find(1);
            $data = [
                'type' => 'basic',
                'name' => $company['first_name'] .$company['last_name'],
                'message' => 'Owner upload a License'
            ];
            $this->notificationService->send($admin , 'NEW License' , 'Owner upload a license' , $data , 'License');
        }catch(Exception $e){
            return ['success' => false , 'msg' => $e];
        }




        return ['success' => true, 'data' => $companyLicense, 'msg' => 'upload successfully'];
    }
} 

    


}