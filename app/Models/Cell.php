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
     * Recalculates the cell's risk score based on associated hazards.
     */
    public function recalculateRiskScore(): void
    {
        // Get hazards that are 'diproses' or 'selesai' and have a risk_score
        $relevantHazards = $this->hazards()
                                ->whereIn('status', ['diproses', 'selesai'])
                                ->whereNotNull('risk_score')
                                ->get();

        $totalScore = $relevantHazards->sum('risk_score');
        $count = $relevantHazards->count();

        $this->total_hazard_risk_score = $totalScore;
        $this->hazard_count = $count;
        $this->risk_score = $count > 0 ? round($totalScore / $count) : 0;
        $this->zone_color = $this->getZoneColorByRiskScore($this->risk_score);
        $this->save();
    }

    /**
     * Determine the zone color based on the risk score.
     * This logic can be refined based on specific business rules.
     */
    private function getZoneColorByRiskScore(int $score): string
    {
        if ($score <= 5) return '#10b981'; // Green for Low Risk
        if ($score <= 10) return '#f59e0b'; // Yellow for Medium Risk
        if ($score <= 15) return '#ef4444'; // Red for Medium-High Risk
        if ($score <= 20) return '#f43f5e'; // Rose for High Risk
        return '#ff1a1a'; // Darker Red for Extreme Risk
    }
}
