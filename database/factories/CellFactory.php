<?php

namespace Database\Factories;

use App\Models\Cell;
use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cell>
 */
class CellFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'map_id' => Map::factory(), // Will create a new map if not overridden
            'col_index' => $this->faker->numberBetween(0, 19), // Assuming max grid size of 20 for now
            'row_index' => $this->faker->numberBetween(0, 19), // Assuming max grid size of 20 for now
            'area_type' => $this->faker->randomElement(['normal', 'restricted', 'entry', 'exit']),
        ];
    }

    public function forMap(Map $map)
    {
        return $this->state(fn (array $attributes) => [
            'map_id' => $map->id,
            'col_index' => $this->faker->numberBetween(0, $map->cols - 1),
            'row_index' => $this->faker->numberBetween(0, $map->rows - 1),
        ]);
    }
}
