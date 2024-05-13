<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = [
        "name",
        "last_name",
        "address_city",
        "phon_number",
    ];
}
