<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\Freelancer;
use App\Models\Service;
use App\Services\PostServiceService;
use Exception;

use App\Http\Requests\ServiceRequest;

class PostServiceController extends Controller
{
    
    use ResponseTrait;
    protected $postService;
    public function __construct(PostServiceService $postService){
        $this->postService = $postService;
    }

    public function postService(ServiceRequest $request)
        {
            try {
            
                $id = auth()->id();
              
                $response = $this->postService->postService($request, $id);
       
                if($response['success']){
                    return $this->successResponse('post service successfully', $response['service']);
                }
                       
               elseif($response['status'] == 401){
                    return $this->failedResponse($response['msg'] , null , 401);
                }
                elseif($response['status'] == 404){
                    return $this->failedResponse($response['msg'] , null , 404);
                }
            } catch (Exception $e) {
                return $this->failedResponse('You have to enter all attributes', $e, 400);
    
            }
        
        }   
        public function updateService(Request $request,$serviceId){
            $id = auth()->id();
            $service = Service::find($serviceId);
            
            $response = $this->postService->updateService($request,$id,$service);
            if ($response['success']) {
                return $this->successResponse($response['msg'], $response['data']);
            } else {
                return $this->failedResponse($response['msg'], null, $response['status']);
            }   
            
            
        }

        public function deleteService($serviceId){
            $id = auth()->id();
            $service = Service::find($serviceId);
            if($service == null){
                return $this->failedResponse('no Service match with your query' ,null);

            }

        try{
            if($service->freelancer_id == $id){
                $service->delete();
               return $this->successResponse('delete service successfully' ,null );
            }
        }
        catch(Exception $e){
            return $this->failedResponse($e ,null);
        }
        }

        public function showService(){
            $id = auth()->id();
            $freelancer = Freelancer::find($id);
            return  $this->successResponse('you have '.count($freelancer->services).' service posts' ,$freelancer->services);;
        }


        
        public function uploadLicense(Request $request){
        
            $id = auth()->id();
            $response = $this->postService->uploadLicense($request , $id);
            if($response['success']){
                return $this->successResponse($response['msg'] , $response['data']);
            }
            elseif($response['status'] == 401){
                return $this->failedResponse($response['msg'] , null , 401);
            }
           
        }

}
