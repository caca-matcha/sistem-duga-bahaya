<?php

namespace App\Jobs;

use App\Models\Map;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateMapCellsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Map $map;

    /**
     * Create a new job instance.
     */
    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->map->cells->each(function ($cell) {
            $cell->recalculateRiskScore();
        });
    }
}
