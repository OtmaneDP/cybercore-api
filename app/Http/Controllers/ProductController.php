<?php

namespace App\Http\Controllers;


use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\HelperTraites\JsonResponseBuilder;
use App\Http\Requests\CreateProductRequest;

class ProductController extends Controller
{
    //


    public function create(CreateProductRequest $request){

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

        return JsonResponseBuilder::successeResponse(
            "product created withe successefully.", 
            Product::find($createdProduct->id)->toArray()
        );
    }

    public function update(CreateProductRequest $request){

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

        return response()->json([
            "message" => "product updated withe succefully...."
        ]);
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

    public function getAll(){
        $allProducts = Product::get();
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
