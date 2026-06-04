<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
             $table->string('mobile', 20)->nullable()->after('password');
        });
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('admin', 'student', 'owner', 'agent', 'advertise')
            NOT NULL DEFAULT 'user'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile');
        });
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('admin', 'student', 'owner')
            NOT NULL DEFAULT 'user'
        ");
    }
};
