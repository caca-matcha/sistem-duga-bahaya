<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'background_image',
        'rows',
        'cols',
        'created_by',
    ];

    /**
     * Get the parent map.
     */
    public function parent()
    {
        return $this->belongsTo(Map::class, 'parent_id');
    }

    /**
     * Get the children maps.
     */
    public function children()
    {
        return $this->hasMany(Map::class, 'parent_id');
    }

    /**
     * Get the user who created the map.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the cells for the map.
     */
    public function cells()
    {
        return $this->hasMany(Cell::class);
    }
}
