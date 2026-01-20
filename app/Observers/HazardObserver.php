<?php

namespace App\Observers;

use App\Models\Hazard;
use App\Models\Cell; // Import the Cell model

class HazardObserver
{
    /**
     * Handle the Hazard "created" event.
     */
    public function created(Hazard $hazard): void
    {
        if ($hazard->cell && in_array($hazard->status, ['diproses', 'selesai'])) {
            $hazard->cell->recalculateRiskScore();
        }
    }

    /**
     * Handle the Hazard "updated" event.
     */
    public function updated(Hazard $hazard): void
    {
        // Only recalculate if risk_score or status relevant to calculation has changed
        if ($hazard->isDirty('risk_score') || ($hazard->isDirty('status') && in_array($hazard->status, ['diproses', 'selesai', 'ditolak']))) {
            if ($hazard->cell) {
                $hazard->cell->recalculateRiskScore();
            }
        }
    }

    /**
     * Handle the Hazard "deleted" event.
     */
    public function deleted(Hazard $hazard): void
    {
        // Recalculate if the deleted hazard was contributing to the cell's risk score
        if ($hazard->cell && in_array($hazard->status, ['diproses', 'selesai'])) {
            $hazard->cell->recalculateRiskScore();
        }
    }

    /**
     * Handle the Hazard "restored" event.
     */
    public function restored(Hazard $hazard): void
    {
        // Recalculate if the restored hazard contributes to the cell's risk score
        if ($hazard->cell && in_array($hazard->status, ['diproses', 'selesai'])) {
            $hazard->cell->recalculateRiskScore();
        }
    }

    /**
     * Handle the Hazard "force deleted" event.
     */
    public function forceDeleted(Hazard $hazard): void
    {
        // Similar to 'deleted', recalculate if it affected the cell's score
        if ($hazard->cell && in_array($hazard->status, ['diproses', 'selesai'])) {
            $hazard->cell->recalculateRiskScore();
        }
    }
}
