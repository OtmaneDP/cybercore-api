<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderRequest;
use App\HelperTraites\JsonResponseBuilder;
use App\Models\CartItem;
use App\Models\Product;

class OrderController extends Controller
{
    //
   
    public function create(CreateOrderRequest $request){
        // create new Customer
        $customerModel = new Customer();
        $createdCustomer = $customerModel->create($request->only($customerModel->fillable));
        
        // create new order associeted withe Created Customer
        $createdOrder = Order::create([
            "user_id" => $request->user_id,
            "customer_id" => $createdCustomer->id,
            "amount" => $request->amount,
        ]);

        // get cart items to place order
        $userCart = Cart::where("user_id", $request->user_id)->get()[0];
        $userCartItems = json_decode($request->cart_cached_items, true);
         
        
        // iterate cart items recived from client cache  
        foreach($userCartItems as $item){
          
            OrderItem::create([
               "order_id" => $createdOrder->id,
               "contete"=> $item["cart_item"]["contete"], 
               "color" => $item["cart_item"]["color"], 
               "product_id" => $item["cart_item"]["product_id"] ,
            ]);
            $targetProduct = Product::where("id", $item["cart_item"]["product_id"])->first();
            $updatedContete = $targetProduct->contete - $item["cart_item"]["contete"];
            $targetProduct->update([
                "contete" => $updatedContete,
            ]);
            if($updatedContete === 0){
                $targetProduct->update([
                    "state" => false,
                ]);
            }
        } 
        
        // delete product from cart items 
        CartItem::where("cart_id", $userCart->id)->delete();
        return JsonResponseBuilder::successeResponse("place order withe succefully", $createdCustomer->get()->toArray());
    }

    public function delete($orderId){

       $targetOrder = Order::find($orderId);

    //    delete order and its informtions using cascade delete
       $targetOrder->customer->delete();
       return JsonResponseBuilder::successeResponse("Order Cancled withe successfully..",[]); 
    }
    
    public function getAll(){
        $orders = Order::get();
        
        foreach($orders as $order){
           array_merge($order->toArray(), $order->products->toArray());
        }

        return JsonResponseBuilder::successeResponse("All order withe its products",$orders->toArray());
    }

    public function checkAvailabelContity(){
        // check if the have availabel contity of  products   
    } 

}
