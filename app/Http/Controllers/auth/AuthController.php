<?php

namespace App\Http\Controllers\Auth;

use App\HelperTraites\JsonResponseBuilder;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {   
        $credentials = $request->validated();

        if (! $token = auth()->attempt($credentials)) {
            return JsonResponseBuilder::errorResponse(HttpFoundationResponse::HTTP_UNAUTHORIZED, "Unauthurized Request");
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {   $userInfo = response()->json(auth()->user());
        return JsonResponseBuilder::successeResponse("user Account informtions",$userInfo->toArray());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return JsonResponseBuilder::successeResponse("suceccufully loged out...",[]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            "status_code" => HttpFoundationResponse::HTTP_ACCEPTED,
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}