<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyLicense;
use App\Models\EmpplyeLicense;
use App\Traits\ResponseTrait;


class AdminController extends Controller
{
    use ResponseTrait;

    public function licenseApproval($licenseId)
    {

        $license = CompanyLicense::find($licenseId);
        if ($license == null) {
            
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='approved';
            $license->save();
            return $this->successResponse('license approved successfully',$license);
        }

    }

    public function licenserejected($licenseId)
    {

        $license = CompanyLicense::find($licenseId);
        if ($license == null) {
            
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='rejected';
            $license->save();
            return $this->successResponse('license rejected successfully',$license);
        }

    }


    public function showlicense(Request $request){
        $company=CompanyLicense::get();
        if($company->isEmpty()){
            return $this->failedResponse('you dont have licenses' , null , 401);
           
        }else{
            return $this->successResponse(' your license',$company);
        }
    }

    ///////////////////////////////////
    public function licenseFreelancerApproval($licenseId)
    {

        $license = EmpplyeLicense::find($licenseId);
        if ($license == null) {
            
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='approved';
            $license->save();
            return $this->successResponse('license approved successfully',$license);
        }

    }

    public function licenseFreelancerRejected($licenseId)
    {

        $license = EmpplyeLicense::find($licenseId);
        if ($license == null) {
            
            return $this->failedResponse('No license match your query', null);
        }else{
            $license->status='rejected';
            $license->save();
            return $this->successResponse('license rejected successfully',$license);
        }

    }


    public function showFreelancerLicense(Request $request){
        $freelancer=EmpplyeLicense::get();
        if($freelancer->isEmpty()){
            return $this->failedResponse('you dont have licenses' , null , 401);
           
        }else{
            return $this->successResponse(' your license',$freelancer);
        }
    }
}
