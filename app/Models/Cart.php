<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ["user_id"];

    public function products(): BelongsToMany{
       return $this->belongsToMany(Product::class, "cart_items");
    }

    public function items(): HasMany {
        return $this->hasMany(CartItem::class);
    }
}
