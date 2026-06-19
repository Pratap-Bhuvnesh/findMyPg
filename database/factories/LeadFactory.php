<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\User;
use App\Models\Pg;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     protected $model = Lead::class;
    public function definition(): array
    {
        return [
            'student_id' => User::where('role', 'student')
                ->pluck('id')
                ->random(),

            'agent_id' => User::where('role', 'agent')
                ->pluck('id')
                ->random(),

            'pg_id' => Pg::pluck('id')->random(),

            'status' => fake()->randomElement([
                'new',
                'contacted',
                'visit_scheduled',
                'joined',
                'rejected',
            ]),
        ];
    }
}
