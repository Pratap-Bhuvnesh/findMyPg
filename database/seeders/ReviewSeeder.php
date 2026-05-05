<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\PG;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', '!=', 'owner')->get();
        $pgs = PG::all();

        // Ensure data exists
        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create(['role' => 'student']);
        }

        if ($pgs->isEmpty()) {
            $pgs = PG::factory()->count(5)->create();
        }

        // Create reviews
        foreach ($pgs as $pg) {
            Review::factory()
                ->count(3)
                ->create([
                    'pg_id' => $pg->id,
                    'user_id' => $users->random()->id,
                ]);
        }
    }
}
