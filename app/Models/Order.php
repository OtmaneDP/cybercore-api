<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
     
    protected $fillable = ["user_id", "customer_id", "amount", "accepted"];
    public function products(): BelongsToMany{

      return $this->belongsToMany(Product::class,"order_items");
    }

    public function customer(): BelongsTo{
      return $this->belongsTo(Customer::class);
    }
}
