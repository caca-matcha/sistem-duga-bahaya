<?php

namespace Database\Seeders;

use App\Models\Cell;
use App\Models\Hazard;
use App\Models\Map;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class HazardMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // Clear existing data (optional, but good for fresh seeding)
        Hazard::truncate();
        Cell::truncate();
        Map::truncate();
        // User::where('email', '!=', 'admin@example.com')->delete(); // Keep admin user if exists

        // Create some regular users (employees)
        $users = User::factory()->count(10)->create([
            'password' => Hash::make('password'), // Set a common password for dummy users
            'role' => 'karyawan', // Assign them the 'karyawan' role
        ]);

        // Create a few maps
        $maps = collect();
        for ($i = 1; $i <= 3; $i++) {
            $mapAttributes = Map::factory()->make([
                'name' => 'Map Area ' . $i . ' ' . $faker->unique()->word,
                'created_by' => $faker->randomElement($users)->id,
            ])->toArray();

            // Ensure name is not duplicated
            while (Map::where('name', $mapAttributes['name'])->exists()) {
                $mapAttributes['name'] = 'Map Area ' . $i . ' ' . $faker->unique()->word;
            }

            $maps->push(Map::create($mapAttributes));
        }

        // For each map, create its cells and then create hazards
        foreach ($maps as $map) {
            // Create cells for the map based on its rows and cols
            $cells = [];
            for ($r = 0; $r < $map->rows; $r++) {
                for ($c = 0; $c < $map->cols; $c++) {
                    $cells[] = Cell::factory()->create([
                        'map_id' => $map->id,
                        'col_index' => $c,
                        'row_index' => $r,
                        'area_type' => $faker->randomElement(['normal', 'restricted', 'entry', 'exit']),
                    ]);
                }
            }

            // Create a "hotspot" cell for this map (e.g., cell at 1,1)
            $hotspotCell = Cell::where('map_id', $map->id)
                                ->where('col_index', 1)
                                ->where('row_index', 1)
                                ->first();
            if (!$hotspotCell && !empty($cells)) {
                // If 1,1 doesn't exist, pick a random cell as hotspot
                $hotspotCell = $faker->randomElement($cells);
            }

            // Create hazards for this map
            $numHazardsForMap = $faker->numberBetween(30, 60); // 30-60 hazards per map

            for ($i = 0; $i < $numHazardsForMap; $i++) {
                $hazardCell = $faker->randomElement($cells);
                $reportingUser = $faker->randomElement($users);

                // Create a few high-risk hazards for the hotspot cell
                if ($hotspotCell && $i < ($numHazardsForMap * 0.2)) { // 20% of hazards go to hotspot
                    Hazard::factory()->forUser($reportingUser)->forMapAndCell($map, $hotspotCell)->create([
                        'tingkat_keparahan' => $faker->numberBetween(4, 5), // High severity
                        'kemungkinan_terjadi' => $faker->numberBetween(4, 5), // High likelihood
                        'skor_resiko' => $faker->numberBetween(4, 5) * $faker->numberBetween(4, 5),
                    ]);
                } else {
                    Hazard::factory()->forUser($reportingUser)->forMapAndCell($map, $hazardCell)->create();
                }
            }
        }

        $this->command->info('Dummy hazard and map data seeded successfully!');
    }
}
