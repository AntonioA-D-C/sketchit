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
        Schema::create('post_has_categories', function(Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('category_id');
            $table->foreign("post_id")->references('id')->on("post");
            $table->foreign("category_id")->references('id')->on("categories");
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
