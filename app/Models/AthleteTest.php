<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name_fr',
        'name_ar',
        'category',
        'unit',
        'description',
    ];

    public function results()
    {
        return $this->hasMany(AthleteTestResult::class);
    }
}