<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "description", 
        "price", "color", "contete", 
        "admin_id", "catigory_id", 
        "ram"
    ]; 
    public function images(): BelongsToMany{

       return $this->belongsToMany(Image::class, "product_images");
    }
}
