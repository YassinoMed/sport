<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class CashRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'gym_id',
        'status',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function userPayments()
    {
        return $this->hasMany(UserPayment::class);
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }
}
