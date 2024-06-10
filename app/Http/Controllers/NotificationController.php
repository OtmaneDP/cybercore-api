<?php

namespace App\Http\Controllers;

use App\HelperTraites\JsonResponseBuilder;
use App\Http\Requests\CreateNotificationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //


    public function create(CreateNotificationRequest $request){
      
        Notification::create([
            "user_id" => $request -> user_id, 
            "content" => $request -> content, 
            "title" => $request -> title
        ]);  

        return JsonResponseBuilder::successeResponse("notification created withe successfully..", []); 
    }

    public function delete(Request $request){
        $request->validate([
          "user_id" => "required|numeric",
          "notification_id" => "required|numeric"
        ]);

        Notification::where([
            "user_id" => $request->user_id, 
            "id" => $request->notification_id,
        ])->delete(); 
        return JsonResponseBuilder::successeResponse("notification deleted withe successfully", []);
    }

    public function getAll(Request $request){
        $request->validate([
            "user_id" => "required|numeric",
        ]);

        $allNotifications = Notification::where("user_id", $request->user_id)->get()->toArray();
        
        return JsonResponseBuilder::successeResponse("get all Notifications", $allNotifications);
    }

    public function update(Request $request){
        $request ->validate([
            "user_id"=> "required|numeric", 
            "is_read" => "required|bool", 
        ]);
        Notification::where([
            "user_id" => $request->user_id, 
        ])->update([
             "is_read" => $request ->is_read,
        ]);
        $allNotifications = Notification::where("user_id", $request->user_id)->get()->toArray();
        return JsonResponseBuilder::successeResponse("notification state updated withe succesfully",$allNotifications);
    }  
}
 