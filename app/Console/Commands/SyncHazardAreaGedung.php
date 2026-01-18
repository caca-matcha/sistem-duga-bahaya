<?php

namespace App\Console\Commands;

use App\Models\Hazard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncHazardAreaGedung extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-hazard-area-gedung';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Synchronizes the 'area_gedung' field in the hazards table with the most current name from its related location's parent.";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Starting to synchronize 'area_gedung' for existing hazards...");

        $updatedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        // Process in chunks to be memory-efficient on large datasets
        DB::transaction(function () use (&$updatedCount, &$skippedCount, &$errorCount) {
            Hazard::with('location.map')
                ->whereNotNull('location_id')
                ->chunkById(200, function ($hazards) use (&$updatedCount, &$skippedCount, &$errorCount) {
                    foreach ($hazards as $hazard) {
                        if (!$hazard->location) {
                            $this->warn("Skipping Hazard ID: <fg=yellow>{$hazard->id}</> - Associated location not found.");
                            $errorCount++;
                            continue;
                        }

                        // Correct Logic: Get the building name from the location's associated map.
                        $correctName = $hazard->location->map ? $hazard->location->map->name : null;

                        // Check if an update is needed
                        if ($hazard->area_gedung !== $correctName && $correctName !== null) {
                            $oldName = $hazard->area_gedung ?: 'NULL';
                            $this->line("Updating Hazard ID: <fg=yellow>{$hazard->id}</>. Old 'area_gedung': '{$oldName}', New: '{$correctName}'.");

                            $hazard->area_gedung = $correctName;
                            $hazard->save();
                            $updatedCount++;
                        } else {
                            $skippedCount++;
                        }
                    }
                });
        });

        $this->info('---------------------------------');
        $this->info('Synchronization complete!');
        $this->info("<fg=green>{$updatedCount} hazard(s) updated.</>");
        $this->comment("{$skippedCount} hazard(s) were already correct and were skipped.");
        if ($errorCount > 0) {
            $this->warn("<fg=red>{$errorCount} hazard(s) were skipped due to missing location data.</>");
        }

        return 0;
    }
}