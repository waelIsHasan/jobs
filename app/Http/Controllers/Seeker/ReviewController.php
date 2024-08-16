<?php

namespace App\Http\Controllers\Seeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Models\Seeker;
use App\Models\Review;
use App\Models\Service;
use App\Http\Requests\ReviewRequest;

class ReviewController extends Controller
{
    use ResponseTrait;
     public function store(ReviewRequest $request,$serviceId){

        $id = auth()->id();

        $existingService = Service::find($serviceId);
        if($existingService == null)
        return $this->failedResponse('no Service match this query', null);

        $existingReview = Review::where('seeker_id',$id)
        ->where('service_id',$serviceId)->first();
     
        if($existingReview){
            return $this->failedResponse('you have already this review', null);
       }
        
       $review= Review::create([
        'seeker_id'=>$id,
        'service_id'=> $serviceId,
        'rating'=>$request->rating,
        'comment'=>$request->comment
    ]);
    return $this->successResponse('review service successfully',$review);
     }


     public function destroy($reviewId){
        $id = auth()->id();

        $existingReview = Review::find($reviewId);
        if($existingReview == null)
        return $this->failedResponse('I can not find this service', null);

        $existingReview = Review::where('seeker_id',$id)
        ->where('id',$reviewId)->first();
     
        if(!$existingReview){
            return $this->failedResponse('you dont have this review', null);
       }
       $existingReview->delete();
       return $this->successResponse('delete review service successfully',null);
     }
    
     
     public function serviceAvgRating($serviceId){
        $avg = Review::where('service_id',$serviceId)->avg('rating');
        if(!$avg){
            return $this->failedResponse('I can not find this service', null);
        }
        return $this->successResponse('avg review service',$avg);
     }
}
