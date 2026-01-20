<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Map;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateMapNamesFromLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-map-names';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Map names (Gedung) based on the location_id_string of their associated locations.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update map names based on location IDs...');

        // Fetch all locations that have a map_id, as they determine the map's name.
        $locations = Location::whereNotNull('map_id')->get();

        // Group locations by map_id to process each map only once.
        $locationsByMap = $locations->groupBy('map_id');

        $updatedCount = 0;
        $unchangedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($locationsByMap as $mapId => $locationsInMap) {
                // We only need one location to determine the map's name.
                $representativeLocation = $locationsInMap->first();

                // Assumption: The column with the pattern is 'location_id_string'.
                $locationIdString = $representativeLocation->location_id_string;
                
                $newName = null;

                // Use regex to find patterns like '_GA', '_GB', etc.
                if (preg_match('/_G([A-Z])$/', $locationIdString, $matches)) {
                    $letter = $matches[1];
                    $newName = "Gedung " . $letter;
                }

                if ($newName) {
                    $map = Map::find($mapId);

                    if ($map) {
                        if ($map->name !== $newName) {
                            $this->line("Updating Map ID: <fg=yellow>{$mapId}</>. Old name: '{$map->name}', New name: '{$newName}'.");
                            $map->name = $newName;
                            $map->save();
                            $updatedCount++;
                        } else {
                            $this->line("Map ID: <fg=cyan>{$mapId}</> already has the correct name: '{$newName}'. Skipping.");
                            $unchangedCount++;
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
            $this->error('Transaction rolled back. No changes were made.');
            return 1; // Return error code
        }

        $this->info('---------------------------------');
        $this->info("Update complete!");
        $this->info("{$updatedCount} map(s) updated.");
        $this->info("{$unchangedCount} map(s) were already correct.");

        return 0; // Return success code
    }
}