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
         // Block 1: Add the new columns first
        Schema::table('pg_images', function (Blueprint $table) {
            $table->string('image_type')->default('building')->after('image_path');
            $table->integer('display_order')->default(1)->after('image_type');
        });

        // Block 2: Apply the unique index now that the columns exist
        Schema::table('pg_images', function (Blueprint $table) {
            $table->unique(['pg_id', 'image_type', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pg_images', function (Blueprint $table) {
           // 1. Drop the foreign key constraint first
            // Replace 'pg_images_pg_id_foreign' with your actual foreign key name if it differs
            $table->dropForeign(['pg_id']); 
            
            // 2. Drop the unique index safely
            $table->dropUnique(['pg_id', 'image_type', 'display_order']);
            
            // 3. Drop the columns
            $table->dropColumn(['image_type', 'display_order']);
            
            // 4. Re-add the foreign key constraint if you are rolling back but keeping the table
            $table->foreign('pg_id')->references('id')->on('pgs')->onDelete('cascade');
        });
    }
};
