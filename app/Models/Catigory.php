<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Catigory extends Model
{
    use HasFactory;
    
    protected $table = "catigorys";
    protected $fillable = [
       "name", "image_id"
    ];

      public function image(): HasOne{
        
        return $this->hasOne(Image::class, "id", "image_id");
      }
}
