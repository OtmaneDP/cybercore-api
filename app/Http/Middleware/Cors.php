<?php

namespace App\Http\Middleware;

use App\HelperTraites\JsonResponseBuilder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {   
        $allowedOrigins = ['http://example.com']; // Replace with your allowed origin

        // Check if the request origin is in the allowed origins list
        $requestOrigin = $request->headers->get('Origin');
        if (in_array($requestOrigin, $allowedOrigins)) {
            // Add headers to the response
            $response = $next($request);
            $response->headers->set('Access-Control-Allow-Origin', $requestOrigin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
            return $response;
        }
    
        // Forbid the request if origin is not allowed
        return response('Unauthorized.', 403);
    }
}
