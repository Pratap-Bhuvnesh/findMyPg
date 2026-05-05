<?php

namespace Database\Factories;

use App\Models\PgFacility;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pg;

/**
 * @extends Factory<PgFacility>
 */
class PgFacilityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'pg_id' => Pg::inRandomOrder()->first()?->id 
                        ?? Pg::factory(),
            'wifi' => fake()->boolean(),
            'ac' => fake()->boolean(),
            'laundry' => fake()->boolean(),
            'parking' => fake()->boolean(),
        ];
    }
}
