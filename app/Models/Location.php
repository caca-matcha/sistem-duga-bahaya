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
        'pic_id',
        'leader_id',
    ];

    /**
     * Get the PIC associated with the location.
     */
    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    /**
     * Get the Leader associated with the location.
     */
    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::updated(function ($location) {
            // Check if fields relevant to Hazard reports have changed
            $relevantFields = ['name', 'location_id_string', 'type', 'map_id'];
            $changes = array_intersect_key($location->getChanges(), array_flip($relevantFields));

            if (! empty($changes)) {
                $updateData = [];

                if (isset($changes['name'])) {
                    $updateData['area_name'] = $changes['name'];
                }
                if (isset($changes['location_id_string'])) {
                    $updateData['area_id'] = $changes['location_id_string'];
                }
                if (isset($changes['type'])) {
                    $updateData['area_type'] = $changes['type'];
                }
                if (isset($changes['map_id'])) {
                    $updateData['map_id'] = $changes['map_id'];
                    // Also update area_gedung (building name)
                    $map = Map::find($changes['map_id']);
                    if ($map) {
                        $updateData['area_gedung'] = $map->name;
                    }
                }

                if (! empty($updateData)) {
                    // Sync all related hazards to keep history consistent (Choice B)
                    Hazard::where('location_id', $location->id)->update($updateData);
                }
            }
        });
    }

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
