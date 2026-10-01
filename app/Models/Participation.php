<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'participated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courseAttendances()
    {
        return $this->hasMany(CourseAttendance::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
