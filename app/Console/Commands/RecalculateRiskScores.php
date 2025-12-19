<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hazard;
use Illuminate\Support\Facades\Log;

class RecalculateRiskScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hazards:recalculate-risk-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate the risk_score for hazards where it is null, based on severity and probability.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to recalculate risk scores for hazards with null values...');

        // Find hazards where risk_score is null but the components for calculation are present
        $hazardsToUpdate = Hazard::whereNull('risk_score')
                                ->whereNotNull('tingkat_keparahan')
                                ->whereNotNull('kemungkinan_terjadi')
                                ->get();

        if ($hazardsToUpdate->isEmpty()) {
            $this->info('No hazards found that need a risk score recalculation. Everything is up to date.');
            return 0;
        }

        $updatedCount = 0;

        foreach ($hazardsToUpdate as $hazard) {
            // Calculate the new risk score
            $newRiskScore = $hazard->tingkat_keparahan * $hazard->kemungkinan_terjadi;

            // Update the hazard record without triggering model events (like updated_at)
            // to avoid confusion. Use a direct query update.
            Hazard::where('id', $hazard->id)->update(['risk_score' => $newRiskScore]);
            
            $updatedCount++;
            $this->line("Updated hazard ID: {$hazard->id} with new risk score: {$newRiskScore}");
        }

        $this->success("Successfully recalculated risk scores for {$updatedCount} hazards.");
        return 0;
    }
}