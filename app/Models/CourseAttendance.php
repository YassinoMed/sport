<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'participation_id',
        'attendance_date',
        'status', // 'present', 'absent', 'excused'
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    // Relation avec la participation
    public function participation()
    {
        return $this->belongsTo(Participation::class);
    }

    // Relation avec l'utilisateur via la participation
    public function user()
    {
        return $this->hasOneThrough(User::class, Participation::class, 'id', 'id', 'participation_id', 'user_id');
    }

    // Relation avec le cours via la participation
    public function course()
    {
        return $this->hasOneThrough(Course::class, Participation::class, 'id', 'id', 'participation_id', 'course_id');
    }
}