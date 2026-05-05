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
        Schema::create('pg_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pg_id')->constrained()->onDelete('cascade');
            $table->boolean('wifi')->default(false);
            $table->boolean('ac')->default(false);
            $table->boolean('laundry')->default(false);
            $table->boolean('parking')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pg_facilities');
    }
};
