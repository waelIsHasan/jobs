<?php
   namespace App\Traits;
   trait ResponseTrait {
    public function successResponse($message , $data , $statusCode = 200){
        if($data != null){
            return response()->json([
               "success" => true,
                "msg" => $message,
                "data" => $data
            ] , $statusCode);
        } 
        return response()->json([
            "success" => true,
             "msg" => $message,
         ] , $statusCode);
    }

    public function failedResponse($message , $errors , $statusCode = 404){
        if($errors != null){
            return response()->json([
               "success" => false,
                "msg" => $message,
                "errors" => $errors
            ] , $statusCode);
        } 
        return response()->json([
            "success" => false,
             "msg" => $message,
         ] , $statusCode);
    }
}