<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'gym_id',
        'capacity',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }

    public function waitlists()
    {
        return $this->hasMany(Waitlist::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'participations')
            ->withPivot(['status', 'participated_at'])
            ->withTimestamps();
    }

    public function coaches()
    {
        return $this->belongsToMany(User::class, 'course_coach', 'course_id', 'coach_id');
    }
}
