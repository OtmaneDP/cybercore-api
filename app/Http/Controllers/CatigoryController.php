<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Catigory;
use App\Models\CatigoryImage;
use Illuminate\Http\Request;

class CatigoryController extends Controller
{
    //
    
    public function create(Request $request){

        $catigory = new Catigory();
        $request->validate([
            "name" => "required|string", 
            "image" => "required|image",
        ]);
        // create new Image in database
        $image = $request->file("image");
        $imagePath = $image->store("images/catigorys", "public");
      
        $createdImage = Image::create([
            "image_path" => $imagePath,
        ]); 
        // create new catigory
        Catigory::create([
            "name" => $request->name, 
            "image_id" => $createdImage->id
        ]);
        return response()->json([
            "message" => "category created withe succefully..."
        ]);
    }

    public function update(Request $request){

        $request->validate([
            "name" => "required|string", 
            "image" => "required|image",
            "catigory_id" => "required|numeric"
        ]);

        $image = $request->file("image");
        
        $imagePath = $image->store("images/catigorys", "public");
        $catigory = new Catigory(); 
        $targetCatigory = $catigory->find($request->catigory_id);
        $targetCatigory->update([
            "name" => $request->name,
        ]);
        $targetCatigory->image()->update([
            "image_path" => $imagePath,
        ]);

        return response()->json([
            "message" => "catigory updated withe succefully..."
        ]);
    }  
    
    public function delete($catigoryId){

        $targetCatigory = Catigory::find($catigoryId);
        $targetCatigory->delete();
       
        return response()->json([
           "message" => "catigory deleted withe succefully..."
        ]);
    }

    public function getAll(){
          
        $allCatigorys = Catigory::get();

        foreach($allCatigorys as $catigory){
          array_merge($catigory->toArray(), $catigory->image->toArray());
        }

        return response()->json([
          "message" => "show all catigorys", 
          "data" => $allCatigorys,
        ]);
    }

    public function getById($catigoryId){

        $catigory = Catigory::find($catigoryId);
        array_merge($catigory->toArray(), $catigory->image->toArray());

        return $catigory == null ? response()->json([
            "error-message" => "catigory not found withe this id","eror",
            "error-code" => 201
        ]) :  response()->json([
            "message" => "catigory details", 
            "data" => $catigory,
        ]);
    }
}
