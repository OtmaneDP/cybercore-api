<?php

namespace App\Http\Controllers;

use App\HelperTraites\JsonResponseBuilder;
use App\Models\Favorit;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    //

    public function addToFavorite(Request $request){
        

        $request->validate([
            "user_id" => "required|numeric",
            "product_id" => "required|numeric",
        ]);
        $credentials = [
            "user_id" => $request->user_id, 
            "product_id" => $request->product_id,
        ];
        Favorit::create($credentials);

        return JsonResponseBuilder::successeResponse("product add to favrite withe successfully",[]);
    } 

    public function popFromFavorite(Request $request){
        $request->validate([
          "user_id" => "required|numeric", 
          "product_id" => "required|numeric",
        ]);

        Favorit::where([

          "user_id" => $request->user_id, 
          "product_id" => $request->product_id, 

        ])->delete();

        return JsonResponseBuilder::successeResponse("product pop from favorite withe successfully",[]);
    }

    public function getAllFavorite(Request $request){

        $userFavorite = $allProducts = Product::select('products.*')
        ->selectRaw('IF(favorits.product_id IS NOT NULL, true, false) AS favorited')
        ->rightJoin('favorits', function ($join) use ($request) {
            $join->on('products.id', '=', 'favorits.product_id')
                 ->where('favorits.user_id', '=', $request->user_id);
        })->get();
        foreach($allProducts as $product){
            array_merge($product->toArray(),$product->images->toArray());
            array_merge($product->toArray(), ["catigory" => $product->catigory->name]);
         }
        return JsonResponseBuilder::successeResponse("get All favorites products", $userFavorite->toArray());
        
    }
}
