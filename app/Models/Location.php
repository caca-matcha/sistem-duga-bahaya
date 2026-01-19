<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_id_string',
        'type',
        'parent_id',
        'map_id',
        'created_by',
        'display_order',
    ];

    /**
     * Get the map that the location belongs to.
     */
    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * Get the parent location.
     */
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Get the child locations.
     */
    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Get the user that created the location.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
