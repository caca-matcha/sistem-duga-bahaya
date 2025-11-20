<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *

     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     */
    protected $casts=[
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];

    /**
     * Relasi: User (karyawan) membuat banyak hazard
     */
    public function hazardsDilaporkan()
    {
        return $this->hasMany(Hazard::class, 'user_id');
    }

     /**
     * Relasi: User (SHE) menangani banyak hazard
     */
    public function hazardsDitangani()
    {
        return $this->hasMany(Hazard::class, 'ditangani_oleh');
    }
    
    }

