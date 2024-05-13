<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements JWTSubject  
{
    use HasFactory, Notifiable;


    protected $fillable = ["email", "password"];
    
    public function getJWTIdentifier(){

        return $this->getKey();
    }
    public function getJWTCustomClaims(){
        
        return [];
    }

  
}
