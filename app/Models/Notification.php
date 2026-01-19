<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_id',
        'title',
        'message',
        'type',
        'is_read',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hazard report that the notification relates to.
     */
    public function report()
    {
        return $this->belongsTo(Hazard::class, 'report_id');
    }
}
