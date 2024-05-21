<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\RegisterRequest;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    
    public function register(RegisterRequest $request){
       
            $createdUser = User::create([
                "email" => $request->email, 
                "password" => Hash::make($request->password),
            ]);
            $this->createUserCart($createdUser->id);
        // }catch(QueryException $ex){

        //     return response()->json([
        //        "error-message" => $ex->getMessage(), 
        //        "error-code" => $ex->getCode()
        //     ]);
        // }
        return response()->json("user created  withe succefuly...");
    }

    public function createUserCart($userId){
        Cart::create([
            "user_id" => $userId
        ]);
    }
}
