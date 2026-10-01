<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'capacity',
        'registration_fee',
        'belt_requirement',
        'weight_categories',
        'start_at',
        'end_at',
        'created_by',
        'is_public',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_public' => 'boolean',
        'weight_categories' => 'array',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function combats()
    {
        return $this->hasMany(Combat::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

