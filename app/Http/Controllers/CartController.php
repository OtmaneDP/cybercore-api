<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HelperTraites\JsonResponseBuilder;
use App\Models\Cart;
use App\Models\CartItem;
class CartController extends Controller
{
    
    public function addToCart(Request $request){

        $request->validate([
            "user_id" => "required|numeric", 
            "product_id" => "required|numeric",
            "color" => "required|string", 
        ]);
        $userCart = Cart::where("user_id",$request->user_id)->get()[0];
        $exictingItem = null;
        foreach($userCart->items as $item){
          if($item->product_id == $request->product_id){
            $exictingItem = $item;
          }
        }
        // $exictingItem = CartItem::where("product_id", $request->product_id)->first();
        // $exictingItem == null ? CartItem::create([
        //     "cart_id" => $userCart->id,
        //     "product_id" => $request->product_id,
        //     "contete" => 1,
        //     "color" => $request->color,
        // ]) : CartItem::where("id", $exictingItem->id)->update([
        //     "contete" => $exictingItem->contete+=1,
        // ]);
        if($exictingItem == null){
            CartItem::create([
                    "cart_id" => $userCart->id,
                    "product_id" => $request->product_id,
                    "contete" => 1,
                    "color" => $request->color,
            ]);
        }else{
            // $cartItem = CartItem::where("id", $exictingItem->id);
            if($exictingItem ->product->contete > $exictingItem->contete){
                $exictingItem->update([
                    "contete" => $exictingItem->contete+=1,
            ]);
            }
        }
        


        return JsonResponseBuilder::successeResponse("push into cart  successfully..",[]);
    }

    public function deleteFromCart(Request $request){

        $request->validate([
            "user_id" => "required|numeric", 
            "product_id" => "required|numeric"
         ]);
         $userCart = Cart::where("user_id", $request->user_id)->first(); 
         foreach($userCart->items as $item){
            $item->product_id == $request->product_id ? $item->delete() : null;
         }
      //    CartItem::where([
      //       "product_id", $request->product_id, 
      //       "user_id"  => $request->user_id,
      //    ])->delete();
  
         return JsonResponseBuilder::successeResponse("pop  from cart withe successfully..",[]);
    }

    public function getCartItems(Request $request){
        $request->validate([
         "user_id" => "required|numeric",
        ]);
        $cartItems =  Cart::where("user_id", $request->user_id)->first()->items;
        
        $responseData = [];
        foreach ($cartItems as $item) {
            $cartItemData = $item->toArray();
            $productData = $item->product->toArray();
            $productImagesData["images"] = $item->product->images->toArray();
    
            $responseData [] =  [
              "cart_item" => $cartItemData, 
              "product"    => array_merge($productData, $productImagesData), 
            ];
        }
        return JsonResponseBuilder::successeResponse(
           "get all items withe succefully..", // message
           $responseData); // Data
    }

    public function updateCartItems(Request $request){
       
        $request->validate([
            "user_id" => "required|numeric", 
            "updated_cart" => "required|json",
        ]);

        $userCart = Cart::where("user_id", $request->user_id)->first();
        
        $updatedCartItems = json_decode($request->updated_cart, true);

        foreach($updatedCartItems as $item){
          CartItem::where([
            "product_id" => $item["cart_item"]["product_id"], 
            "cart_id" => $userCart->id
          ])->update([
            "contete" => $item["cart_item"]["contete"],
          ]);
        }

        return JsonResponseBuilder::successeResponse("cart updated withe succefully..", $userCart->toArray());

    } 
}
