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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->binary("image_content");
            $table->string("image_type");
            $table->string("name",50);
            $table->timestamps();
            
            // foreign key space..
        });

        /*
          this db query to add medium blob to image_content row 
          because laravel dont support medium blob in blueprint schema 
        */
        DB::statement('alter table images modify image_content mediumblob not null'); 
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
