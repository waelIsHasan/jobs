<?php
namespace App\Services;

use App\Models\Service;
use App\Models\Freelancer;
use App\Models\EmpplyeLicense;
use Exception;
use App\Services\NotificationService;
use App\Models\Admin;

class PostServiceService
{
    protected $notificationService;
    public function __construct(NotificationService $notificationService){
        $this->notificationService = $notificationService;
    }

    public function postService($request, $id)
    {

        $freelancer=EmpplyeLicense::where('freelancer_id',$id)->get();
        if($freelancer->isEmpty()){
            return [
                'success' => false,
                'msg' => ' you dont have license ',
                'status' => 404];
        }else{
            $freelancerLicense = $freelancer->where('status','approved')->first();
        
            if($freelancerLicense){
        $service = Service::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price'],
            'estimated_time' => $request['estimated_time'],
            'category_id' => $request['category_id'],
            'freelancer_id' => $id,
        ]);
        $id = auth()->user()->id;
        $freelancer = "App\Models\Freelancer";
        $friends = $freelancer::find($id)->friends;
      
        // notify fcm
        try{ 
        $this->notificationService->notifyFriends($id , $friends ,'Freelancer' ,'PostingService');
        }
        catch(Exception $e){
            return [
                'succes' => false,
                'msg' => $e
            ];
        }
        return [
            'success' => true,
            'msg' => 'You post job successfully',
            'service' => $service
        ];
        }else{
            return [
                'success' => false,
                'msg' => 'check your license first',
                'status' => 401];
        }
    }

    }

    public function updateService($request, $id, $service)
    {
        try {
            if ($service->freelancer_id == $id) {
                $service->update([
                    'name' => (($request['name'] == null && !($request->has('name'))) ? $service['name'] : $request['name']),
                    'description' => (($request['description'] == null && !($request->has('description'))) ? $service['description'] : $request['description']),
                    'price' => (($request['price'] == null && !($request->has('price'))) ? $service['price'] : $request['price']),
                    'estimated_time' => (($request['estimated_time'] == null && !($request->has('estimated_time'))) ? $service['estimated_time'] : $request['estimated_time']),
                    'category_id' => (($request['category_id'] == null && !($request->has('category_id'))) ? $service['category_id'] : $request['category_id']),
                    'freelancer_id' => $id,
                ]);
                return [
                    'success' => true,
                    'msg' => 'You updat your service',
                    'data' => $service
                ];
            } else {

                return [
                    'success' => false,
                    'msg' => 'service is not found',
                    'status' => 400
                ];

            }



        } catch (Exception $e) {
            return [
                'success' => false,
                'msg' => 'do not have permssion',
                'status' => 401
            ];
        }
    }

    public function uploadLicense($request)
    {
        $id = auth()->user()->id;
        $freelancerLicense=EmpplyeLicense::where('freelancer_id',$id)->first();
        $freelancer = Freelancer::find($id);

        if($freelancerLicense){
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

            $path = $request->file('license_file')->storeAs('Freelancerlicenses', date('mdYHis') . uniqid() . $license_file, 'empco_resume');
        }
        $freelancerLicense = EmpplyeLicense::create([
            'license_file' => ("resumes/".$path),
            'freelancer_id' => $id,
        ]);
        try{
            $admin = Admin::find(1);
            $data = [
                'type' => 'basic',
                'name' => $freelancer['first_name'] .$freelancer['last_name'],
                'message' => 'Freelancer upload a License'
            ];
            $this->notificationService->send($admin , 'NEW License' , 'Freelancer upload a license' , $data , 'License');
        }catch(Exception $e){
            return ['success' => false , 'msg' => $e];
        }

        return ['success' => true, 'data' => $freelancerLicense, 'msg' => 'upload successfully'];
        
    
    }
} 

}