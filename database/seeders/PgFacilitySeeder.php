<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pg;
use App\Models\PgFacility;

class PgFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pgs = Pg::all();

        if ($pgs->isEmpty()) {
            $pgs = Pg::factory()->count(5)->create();
        }

        foreach ($pgs as $pg) {
            PgFacility::factory()->create([
                'pg_id' => $pg->id
            ]);
        }
    }
}
