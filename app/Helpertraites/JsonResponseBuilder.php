<?php 

namespace App\HelperTraites;


use App\Helpertraites\ResponseStatusCode;
use Nette\Utils\Callback;
use Symfony\Component\HttpFoundation\Response;

trait  JsonResponseBuilder {
   
  
    public static function successeResponse($message, Array $data){
        return response()->json([
            "state" => "successe",
            "status-code" => Response::HTTP_ACCEPTED, 
            "message" => $message, 
            "data" => $data,
        ]);
    } 

    public static function errorResponse($statusCode, $message){
        return response()->json([
            "state" => "error", 
            "status-code" => $statusCode, 
            "message" => $message, 
        ]);
    }
}