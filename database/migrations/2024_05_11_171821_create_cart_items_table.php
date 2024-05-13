<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->integer("contete");
            $table ->string("color");
            $table->integer("ram");
            
            
            $table->unsignedBigInteger("product_id");
            $table->unsignedBigInteger("cart_id"); 

            $table->foreign("cart_id")->references("id")->on("carts")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("product_id")->references("id")->on("products");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
