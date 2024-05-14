<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Requests\CreateOrderRequest;
use App\HelperTraites\JsonResponseBuilder;

class OrderController extends Controller
{
    //

    public function create(CreateOrderRequest $request){
    
        $createdCustomer = Customer::create($request->all());
        
        $createdOrder = Order::create([
            "user_id" => $request->user_id,
            "customer_id" => $createdCustomer->id,
            "amount" => $request->amount,
        ]);
        // get cart items to place order
        $userCart = Cart::where("user_id", $request->user_id)->get()[0];
        
        foreach($userCart->items as $item){
            OrderItem::create([
               "contete"=> $item->contete, 
               "ram" => $item->product->ram,
               "color" => $item->product->color, 
               "product_id" => $item->product->id ,
               "order_id" => $createdOrder->id
            ]);
            $item->delete();
        } 
        
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

}
