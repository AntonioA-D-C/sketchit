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
        Schema::table('comments', function(Blueprint $table){
            // $table->string('user_name');
           //  $table->string('content', 10000);
            // $table->string('commentable_type');
            // $table->unsignedBigInteger('commentable_id');
           //  $table->dropColumn('post_ID');
           $table->dropColumn('contentable_type');
             $table->string('commentable_type');
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
