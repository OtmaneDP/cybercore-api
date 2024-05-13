<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name",50)->unique();
            $table->longText("description"); 
            $table->integer("price")->default(0); 
            $table->string("color", 200);
            $table->integer("contete");
            $table->string("ram");
            // foreign key space..
            $table->unsignedBigInteger("catigory_id");
            $table->foreign("catigory_id")->references("id")->on("catigorys");
            
            $table->unsignedBigInteger("admin_id");
            $table->foreign("admin_id")->references("id")->on("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
