<?php

use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CatigoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Requests\ProductCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// User accessibale  Routes

Route::group([

    "controller" =>  AuthController::class,
    "middleware" => "api"

],function(){
    Route::post('login', "login");
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('me', 'me');
});
Route::post("register", [RegisterController::class, "register"]);

// catigory routes
Route::group([

   "prefix" => "catigory",
   "controller" => CatigoryController::class
  
],function(){
    Route::get("get/{id}", "getById");
    Route::get("all", "getAll");
    Route::post("update" , "update");
    Route::post("delete/{id}", "delete");
    Route::post("create", "create");
});

// product routes
Route::group([

    "prefix" => "product",
    "middleware" => ["auth:api","adminRole","api"],
    "controller" => ProductController::class,

],function(){
    Route::post("create", "create");
    Route::post("update", "update");
    Route::post("delete/{id}", "delete");
});
Route::group([

    "prefix" => "product",
    "middleware" => ["auth:api","api"],
    "controller" => ProductController::class,

],function(){
    Route::get("get/{id}",  "getById"); 
    Route::get("all",  "getAll");
    Route::post("addToCart",  "addToCart");
    Route::get("deleteFromCart/{id}",  "deleteFromCart");
});

// order routes

Route::group([
   "prefix" => "order",
   "middleware" => ["auth:api","api"],
   "controller" => OrderController::class,

],function(){
   Route::post("order/placeOrder", "create");
   Route::get("order/cancel/{id}", "delete");
}); 
Route::get("order/all" , [OrderController::class, "getAll"])->middleware(["adminRole","auth:api"]);