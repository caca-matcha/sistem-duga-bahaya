<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'cell_id',
        'parameter_name',
        'value',
    ];

    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }
}
