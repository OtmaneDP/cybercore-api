<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\HelperTraites\JsonResponseBuilder;
use App\Http\Requests\CreateProductRequest;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    //


    public function create(CreateProductRequest $request){

        try{
            $product = new Product();
            $createdProduct  = $product->create($request->only($product->fillable));
            
            $productImageModel = new ProductImage();
    
            $images = $request->file("images");
            foreach($images as $image){
                
                $imagePath = $image->store("images/products", "public");
                $createdImage = Image::create([
                    "image_path" => $imagePath,
                ]);
                
                $productImageModel->create([
                    "image_id" => $createdImage->id, 
                    "product_id" => $createdProduct->id,
                ]);
            }

        }catch(QueryException $ex){
            if( $ex->errorInfo[1] == 1062){
                return JsonResponseBuilder::errorResponse(
                Response::HTTP_CONFLICT, 
                "this name of product must be unique");
            }
        }
        array_merge($createdProduct->toArray(),$createdProduct->images->toArray());
        array_merge($createdProduct->toArray(), ["catigory" => $createdProduct->catigory->name]);
        return JsonResponseBuilder::successeResponse(
            "product created withe successefully.", 
            $createdProduct->toArray()
        );
    }

    public function update(CreateProductRequest $request){

        try{

            $request->validate(array_merge(
                $request->rules(),["admin_id"=> "required|numeric"]
            ));
    
            $product = Product::find($request->product_id);
            $product->update($request->only($request->fillable)); 
            
            $productImageModel = new ProductImage();
    
            foreach($product->images as $image){
                $image->delete();
            }
            $images = $request->file("images");
    
            foreach($images as $image){
    
              
                $imagePath = $image->store("images/products", "public");
                $createdImage = Image::create([
                    "image_path" => $imagePath,
                ]);
                $productImageModel->create([
                    "image_id" => $createdImage->id, 
                    "product_id" => $product->id,
                ]);
            }
            $updatedProduct = $product->refresh();
            array_merge($updatedProduct->toArray(),$updatedProduct->images->toArray());
            array_merge($updatedProduct->toArray(), ["catigory" => $updatedProduct->catigory->name]);

        }catch(QueryException $ex){
            if( $ex->errorInfo[1] == 1062){
                return JsonResponseBuilder::errorResponse(
                Response::HTTP_CONFLICT, 
                "this name of product must be unique");
            }
        }
        
        return JsonResponseBuilder::successeResponse("product updated withe succefully....", $updatedProduct->toArray());
    }

    public function delete($productId){ 

        $product = Product::find($productId);
        foreach($product->images as $image){
            $image->delete();
        }

        $product->delete();

        return response()->json([
            "message" => "product deleted withe succefully...."
        ]);
    }

    public function getAll(Request $request){
        
        $request->validate([
            "user_id" => "required|numeric",
        ]);
        $allProducts = Product::select('products.*')
        ->selectRaw('IF(favorits.product_id IS NOT NULL, true, false) AS favorited')
        ->leftJoin('favorits', function ($join) use ($request) {
            $join->on('products.id', '=', 'favorits.product_id')
                 ->where('favorits.user_id', '=', $request->user_id);
        })->get();

        foreach($allProducts as $product){
           array_merge($product->toArray(),$product->images->toArray());
           array_merge($product->toArray(), ["catigory" => $product->catigory->name]);
        }
        return response()->json([
            "message" => "get all products", 
            "data" => $allProducts, 
        ]);
    }

    public function getById($productId){
        $product = Product::find($productId);
        array_merge($product->toArray(), $product->images->toArray());
        return response()->json([
            "message" => "product details", 
            "data" => $product
        ]);
    }
}
