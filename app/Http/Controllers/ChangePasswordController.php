<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\HelperTraites\JsonResponseBuilder;
use App\Http\Requests\ChangePasswordRequest;
use Symfony\Component\HttpFoundation\Response;

class ChangePasswordController extends Controller
{
    //
    public function change(ChangePasswordRequest $request){

        if(Hash::check($request->current_password,auth()->user()->password)){
            User::find($request->user_id)->update([

                "password" => Hash::make($request->new_password),
            ]);
            return JsonResponseBuilder::successeResponse("the password updated withe suceccefully",[]);
        }

        return JsonResponseBuilder::errorResponse(Response::HTTP_FORBIDDEN, "password incorrect");
    }

    public function sendToken(Request $request){
        $request->validate([
          "email" => "required|string"
        ]);
        //  generate reset code and send it in email 
        if(User::where("email",$request->email)->first() != null){
            $randomDegit = random_int(100000,999999);
            Cache::put("reset_password_".$request->email, $randomDegit, now()->addMinutes(3));
            // this section for send Email
            Mail::to($request->email)->send(new ResetPasswordMail($randomDegit));
            return JsonResponseBuilder::successeResponse("check your email you hve recived reset token consiste of sex degits",[]);
        }
        return JsonResponseBuilder::errorResponse(Response::HTTP_FORBIDDEN, "indefined  email");
    }

    public function verifyToken(Request $request){
        $request->validate([
            "reset_token" => "required|numeric|max_digits:6|min_digits:6",
            "email" => "required|string"
        ]);
        $cachedToken = Cache::get("reset_password_".$request->email);
        if($request->reset_token == $cachedToken){
            return JsonResponseBuilder::successeResponse("token vlidation withe succefully",[]);
        }
        return JsonResponseBuilder::errorResponse(Response::HTTP_FORBIDDEN, "indefined  token", );
    }
    public function resetPassword(Request $request){
        $request->validate([
            "reset_token" => "required|numeric|max_digits:6|min_digits:6",
            "reset_password" => "required|string",
            "email" => "required|string",
        ]);
        $targetCachedToken = "reset_password_".$request->email;
        if(Cache::get($targetCachedToken) === null){
            return JsonResponseBuilder::errorResponse(Response::HTTP_FORBIDDEN,"invalid token");
        }
        User::where("email", $request->email)->first()->update([
            "password" => Hash::make($request->reset_password),
        ]);
        Cache::forget($targetCachedToken);
        return JsonResponseBuilder::successeResponse("reset password withe succefully..",[]);
    } 
}
