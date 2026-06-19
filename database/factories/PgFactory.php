<?php

namespace Database\Factories;

use App\Models\Pg;
use App\Models\User;
use App\Models\University;
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
        $universityObject =  University::inRandomOrder()->first() ?? University::factory()->create();
        $lat1 = 28.6139; // Example latitude for Delhi
        $lon1 = 77.2090; // Example longitude for Delhi
        $lat2 = $this->faker->latitude(8.4, 37.6);;
        $lon2 = $this->faker->longitude(68.7, 97.2);       
        $distanceBetween=  $this->calculateDistance($lat1, $lon1, $universityObject->latitude, $universityObject->longitude, 'km') ;
        
       return [
            'owner_id' => User::where('role', 'owner')->inRandomOrder()->first()?->id 
            ?? User::factory()->create(['role' => 'owner'])->id,
            'name' => $this->faker->company . ' PG',
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(3000, 15000),
            'location' => $this->faker->city,
            'latitude' => $lat2, 
            // Generates a valid longitude between -180 and 180
            'longitude' => $lon2,
            'university_id' => $universityObject->id,
            'distance' => $distanceBetween, // Distance in kilometers
            'food_available' => $this->faker->boolean,
            'gender' => $this->faker->randomElement(['Boys', 'Girls', 'Co-ed']),
            'is_verified' => $this->faker->boolean,
            'accomodation_sharing_prices' => [
                'Single-sharing' => $this->faker->numberBetween(8000, 15000),
                'Double-sharing' => $this->faker->numberBetween(6000, 12000),
                'Triple-sharing' => $this->faker->numberBetween(4000, 9000),
            ],

            'accomodation_type' => $this->faker->randomElement([
                'pg',
                'hostel',
                'apartment',
                 'home',
            ]),
        ];
    }
    function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km') 
    {
        $earthRadius = ($unit === 'km') ? 6371 : 3959; // Kilometers or Miles

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
            
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Returns distance
    }
}
