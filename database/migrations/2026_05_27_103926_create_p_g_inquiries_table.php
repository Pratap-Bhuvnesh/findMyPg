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
        Schema::create('pg_inquiries', function (Blueprint $table) {
            $table->id();

             // PG Relation
            $table->foreignId('pg_id')->constrained('pgs')->cascadeOnDelete();
            // Owner Relation
            //$table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('student_name');

            $table->string('student_phone');

            $table->string('student_email')->nullable();

            $table->text('message')->nullable();

            $table->enum('status', [
                'new',
                'contacted',
                'visited',
                'joined',
                'closed'
            ])->default('new');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pg_inquiries');
    }
};
