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
        Schema::table('pg_facilities', function (Blueprint $table) {
            $table->dropColumn(['wifi', 'ac', 'laundry', 'parking']);              
            $table->string('amenities', 50)->nullable()->after('pg_id');
            $table->boolean('available')->default(false)->after('amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pg_facilities', function (Blueprint $table) {
            $table->boolean('wifi')->default(false);
            $table->boolean('ac')->default(false);
            $table->boolean('laundry')->default(false);
            $table->boolean('parking')->default(false);         
            $table->dropColumn(['available', 'amenities']);              
        });
    }
};
