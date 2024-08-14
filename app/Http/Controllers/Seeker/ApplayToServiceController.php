<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Traits\ResponseTrait;

class ApplayToServiceController extends Controller
{
    use ResponseTrait;
    
    public function serviceSearsh(Request $request){
        $searsh = $request->only('name','price','description');
        $service = Service::searsh( $searsh)->get();
         return $this->successResponse('searsh service' , $service );
    }
}
