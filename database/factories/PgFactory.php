<?php

namespace Database\Factories;

use App\Models\Pg;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pg>
 */
class PgFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'owner_id' => User::where('role', 'owner')->inRandomOrder()->first()?->id 
              ?? User::factory()->create(['role' => 'owner'])->id,
            'name' => $this->faker->company . ' PG',
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(3000, 15000),
            'location' => $this->faker->city,
            'food_available' => $this->faker->boolean,
        ];
    }
}
