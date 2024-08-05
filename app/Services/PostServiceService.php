<?php
namespace App\Services;

use App\Models\Service;
use App\Models\Freelancer;
use App\Models\EmpplyeLicense;
use Exception;

class PostServiceService
{

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
                'status' => 400
            ];
        }
    }

    public function uploadLicense($request, $id)
    {

        $freelancer=EmpplyeLicense::where('freelancer_id',$id)->first();
        if($freelancer){
            return [
                'success' => false,
                'msg' => 'you have license alredy',
                'status' => 401];
        }else{
        $request->validate([
            'license_file' => 'required|file',
        ]);

        if ($request->hasFile('license_file')) {
            $license_file = $request->file('license_file')->getClientOriginalName();
            // upload to server

            $path = $request->file('license_file')->storeAs('', date('mdYHis') . uniqid() . $license_file, 'empco_resume');

        }
        $freelancerLicense = EmpplyeLicense::create([
            'license_file' => ("license_file/" . $path),
            'freelancer_id' => $id,
        ]);
        return ['success' => true, 'data' => $freelancerLicense, 'msg' => 'upload successfully'];
    }
} 

}