<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "image_path",
    ];
    
    public function getImagePathAttribute()
    {

        $localhostUrl = 'http://localhost:8000/storage';
        return $localhostUrl . '/' . $this->attributes["image_path"];
    }
}
