<?php

namespace App\Services;
use App\Models\Owner;
use App\Models\Freelancer;
use App\Models\Seeker;
class CheckService {

    public function check($user){
            // Get the authenticated user

    // Determine the user model
    if ($user instanceof \App\Models\Owner) {
        return Owner::class;

    } else if ($user instanceof \App\Models\Freelancer) {
        return Freelancer::class;
    
    } else if ($user instanceof \App\Models\Seeker) {
        return Seeker::class;        
    }

}


}