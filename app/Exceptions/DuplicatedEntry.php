<?php

namespace App\Exceptions;

use Exception;

class DuplicatedEntry extends Exception
{
    
    public function render($request, Throwable $e){

        if ($e instanceof QueryException && $e->getCode() == 23000) {
           
            // Handle duplicated key error
            return JsonResponseBuilder::errorResponse(Response::HTTP_CONFLICT,"duplicated entrys");
        }
        return parent::render($request, $e);
    }
}
