<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteTestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'athlete_test_id',
        'value',
        'measured_at',
        'notes',
    ];

    protected $casts = [
        'measured_at' => 'datetime',
    ];

    public function test()
    {
        return $this->belongsTo(AthleteTest::class, 'athlete_test_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}