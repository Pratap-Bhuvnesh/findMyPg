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
        if (!Schema::hasColumn('users', 'mobile')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('mobile', 20)->nullable()->after('password');                
            });
        }
        if (!Schema::hasColumn('pgs', 'active')) {
            Schema::table('pgs', function (Blueprint $table) {
                 $table->enum('active', ['0', '1'])->default('1')->after('is_verified');
            });
        }
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('admin', 'student', 'owner', 'agent', 'advertise')
            NOT NULL DEFAULT 'student'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'mobile')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('mobile');
            });
        }  
         if (Schema::hasColumn('pgs', 'active')) { 
            Schema::table('pgs', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }   
       /*  DB::statement("UPDATE `pgs` SET `role`='student' WHERE `role`='agent'");     
        DB::statement("UPDATE `pgs` SET `role`='student' WHERE `role`='advertise'");     
        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM('admin', 'student', 'owner')
        "); */
    }
};
