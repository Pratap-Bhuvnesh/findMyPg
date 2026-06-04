<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pg;
use App\Models\User;

class PgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$roles = ['owner', 'student'];
         // Ensure we have some owners
        $owners = User::where('role', 'owner')->get();

        if ($owners->isEmpty()) {
            $owners = User::factory()->count(5)->create([
                 'role' => 'owner',
            ]);
        }

        // Create PGs for each owner
        foreach ($owners as $owner) {
            Pg::factory()->count(100)->create([
                'owner_id' => $owner->id
            ]);
        }
    }    
}
