<?php

namespace Database\Factories;

use App\Models\PGInquiry;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\PG;
/**
 * @extends Factory<PGInquiry>
 */
class PGInquiryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pg_id' => PG::inRandomOrder()->value('id'),

            'student_name' => fake()->name(),

            'student_phone' => fake()->numerify('98########'),

            'student_email' => fake()->safeEmail(),

            'message' => fake()->sentence(),

            'status' => fake()->randomElement([
                'new',
                'contacted',
                'visited',
                'joined',
                'closed'
            ]),
        ];
    }
}
