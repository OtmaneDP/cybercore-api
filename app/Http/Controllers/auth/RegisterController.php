<?php

namespace App\Http\Controllers\auth;

use App\HelperTraites\JsonResponseBuilder;
use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterRequest;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    
    public function register(RegisterRequest $request){
         try{
            $createdUser = User::create([
                "email" => $request->email, 
                "password" => Hash::make($request->password),
            ]);
            $this->createUserCart($createdUser->id);
        }catch(QueryException $ex){
            if( $ex->errorInfo[1] == 1062){
                return JsonResponseBuilder::errorResponse(
                Response::HTTP_CONFLICT, 
                "this email is already exicte try withe other email");
            }
        }
        return JsonResponseBuilder::successeResponse(
            "user created withe succefully..",
            [],
        );
    }

    public function createUserCart($userId){
        Cart::create([
            "user_id" => $userId
        ]);
    }
}
