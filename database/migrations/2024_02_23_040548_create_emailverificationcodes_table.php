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
        Schema::create('emailverificationcodes', function (Blueprint $table) {
   
            
            $table->id();
            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger("userID");
            $table->foreign('userID')->references('id')->on('users')->cascadeOnDelete()->change();
            $table->string('email');
            $table->timestamp('checked_at')->nullable();
     
            $table->string('otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emailverificationcodes');
    }
};
