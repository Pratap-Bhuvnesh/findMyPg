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
             $table->enum('gender', ['Boys', 'Girls','Co-ed'])->default("boys")->after('location')->comment('Gender of allowed in PG');
             $table->boolean('is_verified')->default(0)->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pgs', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('is_verified');
        });
    }
};
