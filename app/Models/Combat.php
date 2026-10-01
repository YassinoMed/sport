<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combat extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_a_id',
        'athlete_b_id',
        'event_id',
        'winner_id',
        'score',
        'status',
        'weight_kg',
        'scheduled_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'weight_kg' => 'decimal:2',
        'scheduled_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function winner()
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function athleteA()
    {
        return $this->belongsTo(User::class, 'athlete_a_id');
    }

    public function athleteB()
    {
        return $this->belongsTo(User::class, 'athlete_b_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

