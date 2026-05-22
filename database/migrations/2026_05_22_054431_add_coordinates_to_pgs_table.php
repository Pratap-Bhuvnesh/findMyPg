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
        Schema::table('pgs', function (Blueprint $table) {
            // Latitude: total 10 digits, 8 after the decimal point (e.g., -90.00000000 to 90.00000000)
            $table->decimal('latitude', 10, 8)->nullable()->after('location');
            
            // Longitude: total 11 digits, 8 after the decimal point (e.g., -180.00000000 to 180.00000000)
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pgs', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
