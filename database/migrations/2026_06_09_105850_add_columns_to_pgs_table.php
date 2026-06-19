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
            $table->json('accomodation_sharing_prices')->default(json_encode([
                'Single-sharing' => '',
                'Double-sharing' => '',
                'Triple-sharing' => '',
            ]))->after('food_available');
            $table->string('accomodation_type')->default('pg')->after('accomodation_sharing_prices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pgs', function (Blueprint $table) {
            $table->dropColumn('accomodation_sharing_prices');
            $table->dropColumn('accomodation_type');
        });
    }
};
