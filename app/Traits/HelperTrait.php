<?php
   namespace App\Traits;
   use Illuminate\Support\Facades\Config;

   trait HelperTrait {

    public function dynamicCheck($request , $x , $str){
        return (($request[$str] == null && !($request->has($str))) ? $x[$str] : $request[$str]);
    }

}