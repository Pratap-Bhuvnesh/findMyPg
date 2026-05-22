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
        $allAmenities = ['wifi', 'ac', 'laundry', 'parking', 'gym', 'cctv', 'power_backup'];
        return [
            'pg_id' => Pg::inRandomOrder()->first()?->id ?? Pg::factory(),
            'amenities' => fake()->randomElement($allAmenities), // Single item
            'available' => fake()->boolean(),
        ];
    }

    /**
     * Generate multiple distinct amenity rows for a single PG.
     */
    public function createMultipleRows(int $count = 3, ?int $pgId = null): void
    {
        $allAmenities = ['wifi', 'ac', 'laundry', 'parking', 'gym', 'cctv', 'power_backup'];
        
        // Pick a single PG ID to share across all rows
        $finalPgId = $pgId ?? (Pg::inRandomOrder()->first()?->id ?? Pg::factory()->create()->id);
        
        // Pick unique random amenities so you don't get duplicates for the same PG
        $selectedAmenities = fake()->randomElements($allAmenities, min($count, count($allAmenities)));

        foreach ($selectedAmenities as $amenity) {
            $this->create([
                'pg_id' => $finalPgId,
                'amenities' => $amenity,
                'available' => fake()->boolean() ? 1 : 0,
            ]);
        }
    }
}
