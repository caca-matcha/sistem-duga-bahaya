<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'row_index',
        'col_index',
        'location_id',
        'area_id',
        'area_name',
        'area_type',
        'risk_score',
        'total_hazard_risk_score', // Added for accumulation
        'hazard_count',            // Added for accumulation
        'zone_color',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function riskParameters()
    {
        return $this->hasMany(RiskParameter::class);
    }

    /**
     * Get the location associated with the cell.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Define the relationship with Hazard models.
     */
    public function hazards(): HasMany
    {
        return $this->hasMany(Hazard::class);
    }

    /**
     * Recalculates the cell's risk score based on associated hazards OR linked building risks.
     */
    public function recalculateRiskScore(): void
    {
        // 1. Handling for Factory Cells linked to Buildings
        $gedungMapId = $this->metadata['gedung_map_id'] ?? null;
        if ($gedungMapId) {
            $targetMap = Map::find($gedungMapId);
            if ($targetMap) {
                $cells = $targetMap->cells()->where('risk_score', '>', 0)->get();
                if ($cells->count() > 0) {
                    $this->risk_score = round($cells->avg('risk_score'));
                } else {
                    $this->risk_score = 0;
                }
                $this->zone_color = $this->getZoneColorByRiskScore($this->risk_score);
                $this->save();

                return; // Done for factory cell
            }
        }

        // 2. Standard Hazard-based Recalculation (for Building/Area cells)
        $relevantHazards = $this->hazards()
            ->whereIn('status', ['diproses', 'selesai'])
            ->whereNotNull('risk_score')
            ->get();

        $totalScore = $relevantHazards->sum('risk_score');
        $count = $relevantHazards->count();

        $this->total_hazard_risk_score = $totalScore;
        $this->hazard_count = $count;
        $this->risk_score = $count > 0 ? round($relevantHazards->avg('risk_score')) : 0; // Changed from max to avg
        $this->zone_color = $this->getZoneColorByRiskScore($this->risk_score);
        $this->save();

        // 3. Trigger parent factory cell recalculation if this results in a change
        $this->triggerParentRecalculation();
    }

    /**
     * Find any cell in a Pabrik map that links to this map and recalculate it.
     */
    public function triggerParentRecalculation(): void
    {
        // Find cells in other maps that link to THIS map via metadata
        $parentCells = Cell::whereJsonContains('metadata->gedung_map_id', (string) $this->map_id)->get();

        foreach ($parentCells as $parentCell) {
            $parentCell->recalculateRiskScore();
        }
    }

    /**
     * Determine the zone color based on the risk score using centralized helper.
     */
    private function getZoneColorByRiskScore(int $score): string
    {
        return getRiskColor($score);
    }
}
