<?php

use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatigoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\FavoritController;
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

Route::post("changepassword",[ChangePasswordController::class, "change"])->middleware(["api","auth:api"]);
Route::post("sendtoken", [ChangePasswordController::class, "sendToken"]);
Route::post("verifytoken", [ChangePasswordController::class, "verifyToken"]);
Route::post("resetpassword", [ChangePasswordController::class, "resetPassword"]);

// catigory routes
Route::group([

   "prefix" => "catigory",
   "controller" => CatigoryController::class, 
   "middleware" => ["auth:api","adminRole","api"],

],function(){
    Route::post("update" , "update");
    Route::post("delete/{id}", "delete");
    Route::post("create", "create");
    Route::get("get/{id}", "getById");
});
Route::group([

    "prefix" => "catigory",
    "controller" => CatigoryController::class,
    "middleware" => ["auth:api","api"],
   
 ],function(){
    Route::get("all", "getAll");
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
});

Route::group([
    "prefix" => "product", 
    "middleware" => ["auth:api", "api"], 
    "controller" => FavoritController::class
],function () {
    Route::post("addToFavorite", "addToFavorite");
    Route::post("popFromFavorite", "popFromFavorite"); 
    Route::post("favorite" , "getAllFavorite");
});

// order routes

Route::group([
   "prefix" => "order",
   "middleware" => ["auth:api","api"],
   "controller" => OrderController::class,

],function(){
   Route::post("placeOrder", "create");
   Route::get("cancel/{id}", "delete");
}); 
Route::get("order/all" , [OrderController::class, "getAll"])->middleware(["adminRole","auth:api"]);


// cart routes

Route::group([

    "middleware" => ["auth:api","api"],
    "controller" => CartController::class,
 
], function (){
    Route::post("cartItems/update", "updateCartItems");
    Route::post("product/addToCart",  "addToCart");
    Route::post("product/deleteFromCart",  "deleteFromCart");
    Route::get("product/inCartItems", "getCartItems");
});