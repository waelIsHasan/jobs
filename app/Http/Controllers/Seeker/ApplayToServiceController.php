<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Traits\ResponseTrait;
use App\Models\Freelancer;

class ApplayToServiceController extends Controller
{
    use ResponseTrait;
    
    public function serviceSearsh(Request $request){
        $searsh = $request->only('name','price','description');
        $service = Service::searsh( $searsh)->get();
         return $this->successResponse('searsh service' , $service );
    }

    public function showServiceByFreelancer($id){
        $freelancer = Freelancer::find($id);
        if($freelancer == null){
            return $this->failedResponse('No Freelancer match this query' , null);
        }
        $curServices = $freelancer->services;

        return $this->successResponse('services' , $curServices);

    }
    public function showOneService($id){
        $service = Service::find($id);

   if($service == null){
            return $this->failedResponse('No service match this query' , null);
        }
        return $this->successResponse('one-service' , $service);

    }
}
