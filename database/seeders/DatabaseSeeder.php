<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PgSeeder;
use Database\Seeders\PgFacilitySeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\PgImageSeeder;
use Database\Seeders\PGInquirySeeder;
use Database\Seeders\UniversitySeeder;
use Database\Seeders\LeadSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call([
            UniversitySeeder::class,
            PgSeeder::class,
            PgFacilitySeeder::class,
            ReviewSeeder::class,
			PgImageSeeder::class,
            PGInquirySeeder::class, 
            LeadSeeder::class,                       
        ]);
    }
}
