<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "description", 
        "price", "color", "contete", 
        "admin_id", "catigory_id", 
    ]; 
    public function images(): BelongsToMany{

       return $this->belongsToMany(Image::class, "product_images");
    }
    public function catigory() : BelongsTo{
        return $this->belongsTo(Catigory::class);
    }

    public function getColorAttribute(){
       return json_decode($this->attributes["color"]);
    }
}
