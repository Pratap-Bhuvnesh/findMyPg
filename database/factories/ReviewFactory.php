<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\PG;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', '!=', 'owner')->inRandomOrder()->first()?->id 
                        ?? User::factory()->create(['role' => 'student'])->id,
                        
            'pg_id' => PG::inRandomOrder()->first()?->id 
                        ?? PG::factory(),

            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional()->sentence(),
        ];
    }
}
