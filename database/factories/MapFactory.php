<?php

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Map>
 */
class MapFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Map ' . $this->faker->randomNumber(2),
            'type' => $this->faker->randomElement(['Building', 'Floor', 'Area', 'Zone']),
            'rows' => $this->faker->numberBetween(5, 20), // Grid size
            'cols' => $this->faker->numberBetween(5, 20), // Grid size
            'parent_id' => null, // Will be set later for sub-maps if needed
        ];
    }

    public function withParent(Map $parent)
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $parent->id,
            'type' => $this->faker->randomElement(['Floor', 'Area', 'Zone']), // Sub-maps are usually not 'Building'
        ]);
    }
}
