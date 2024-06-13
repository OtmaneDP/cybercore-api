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
use App\Models\Notification;
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
       $targetOrder->customer->delete();
       $targetOrder->delete();
       return JsonResponseBuilder::successeResponse("Order Cancled withe successfully..",[]); 
    }
    
    public function accept(Request $request){
        $request ->validate([
            "user_id" => "required|numeric", 
            "order_id"=> "required|numeric"
        ]);

        $targetOrder = Order::where([
            "user_id" => $request->user_id, 
            "id" => $request ->order_id
        ])->first();
        
        $targetOrder->update([
            "accepted"  => true,
        ]);
        Notification::create([
            "user_id" => $request->user_id, 
            "title" => "Order Accepted", 
            "content" => "Your Order has Accepted and it gen a be shipped max in 48h orderID=".$targetOrder->id, 
        ]);

        return JsonResponseBuilder::successeResponse("Order accepted withe successfully..",[]); 
    }
    public function reject(Request $request){
        $request ->validate([
            "user_id" => "required|numeric", 
            "order_id"=> "required|numeric"
        ]);

        $targetOrder = Order::where([
            "user_id" => $request->user_id, 
            "id" => $request ->order_id
        ])->first();

        $targetOrder->customer->delete();
        $targetOrder->delete();
        // send notification after reject Order
        Notification::create([
            "user_id" => $request->user_id, 
            "title" => "Order Rejcted", 
            "content" => "Sorry your Order  has Rejectd  orderID=".$targetOrder->id, 
        ]);
        return JsonResponseBuilder::successeResponse("Order Rjected  withe successfully..",[]); 
    }
    public function getAll(){
        $orders = Order::get();
        
        foreach($orders as $order){
            foreach($order->products as $product){
                array_merge($product->toArray(), $product->images->toArray());
            }
           array_merge($order->toArray(), $order->products->toArray());
           array_merge($order->toArray(), $order->customer->toArray());
           
        }

        return JsonResponseBuilder::successeResponse("All order withe its products",$orders->toArray());
    }
    
    public function getOrdersByUserId($userId){

        $orders = Order::where([
            "user_id" => $userId
        ])->get();
        
        foreach($orders as $order){
            foreach($order->products as $product){
                array_merge($product->toArray(), $product->images->toArray());
            }
           array_merge($order->toArray(), $order->products->toArray());
           array_merge($order->toArray(), $order->customer->toArray());
           
        }

        return JsonResponseBuilder::successeResponse("All order by user Id withe its products",$orders->toArray());

    }
}
