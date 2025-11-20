<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'row_index',
        'col_index',
        'area_id',
        'area_name',
        'area_type',
        'risk_score',
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
}
