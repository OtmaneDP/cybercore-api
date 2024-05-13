<?php

namespace App\Http\Middleware;

use App\HelperTraites\JsonResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {    
        if(auth()->user() != null && auth()->user()->role === "admin"){
            return $next($request);
        }
        return JsonResponseBuilder::errorResponse(Response::HTTP_UNAUTHORIZED,"Unauthurized request");
    }
}
