<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyLicense;
use App\Models\EmpplyeLicense;
use App\Traits\ResponseTrait;
use App\Models\Freelancer;
use App\Models\Notification;
use App\Models\Owner;



class AdminController extends Controller
{
    use ResponseTrait;

    public function licenseApproval($ownerId)
    {
        $owner = Owner::find($ownerId);
        $license = $owner->License;
        if ($license == null) {         
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='approved';
            $license->save();
            return $this->successResponse('license approved successfully',$license);
        }
    }

    public function licenserejected($ownerId)
    {
        $owner = Owner::find($ownerId);
        $license = $owner->License;

        if ($license == null) {
            
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='rejected';
            $license->save();
            return $this->successResponse('license rejected successfully',$license);
        }
    }


    public function showlicense(Request $request){

        $licenses = CompanyLicense::get();
        if($licenses->isEmpty()){
            return $this->failedResponse('you dont have licenses' , null , 401);
           
        }else{
            $arr = [];
            foreach($licenses as $license){
                $license['name'] =Owner::find($license->owner_id)->first_name." ".Owner::find($license->owner_id)->last_name;

                $arr[] = $license;
            }

            return $this->successResponse(' your license',(count($arr) == 0) ? $licenses : $arr);
        }
    }

    ///////////////////////////////////
    public function licenseFreelancerApproval($freelancerId)
    {
        $freelancer = Freelancer::find($freelancerId);
        $license = $freelancer->License;

        if ($license == null) {
            
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='approved';
            $license->save();
            return $this->successResponse('license approved successfully',$license);
        }

    }

    public function licenseFreelancerRejected($freelancerId)
    {
       
       
        $freelancer = Freelancer::find($freelancerId);
        $license = $freelancer->License;
    
        if ($license == null) {
            
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='rejected';
            $license->save();
            return $this->successResponse('license rejected successfully',$license);
        }

    }


    public function showFreelancerLicense(Request $request){
        $licenses = EmpplyeLicense::get();
        if($licenses->isEmpty()){
            return $this->failedResponse('you dont have licenses' , null , 401);
           
        }else{
            
            $arr = [];
            foreach($licenses as $license){
                $license['name'] =Freelancer::find($license->freelancer_id)->first_name." ".Freelancer::find($license->freelancer_id)->last_name;
                $arr[] = $license;
            }
            return $this->successResponse(' your license',(count($arr) == 0) ? $licenses : $arr);
        }
    }
    public function showNotifications(){  
        $id = auth()->user()->id;
        $user = auth()->user();  
        $notifications = Notification::where('notifiable_id' , $id)->where('notifiable_type' , class_basename($user))->get();
        return $this->successResponse('notifications' , $notifications);
    }
}
