<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Belt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'belt_name',
        'training_grade',
        'achieved_at',
        'exam_at',
        'user_id',
        'price_cents',
        'cash_register_id',
    ];

    protected $casts = [
        'achieved_at' => 'date',
        'exam_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}
