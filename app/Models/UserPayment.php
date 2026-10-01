<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'invoice_number',
        'amount_cents',
        'billing_month',
        'start_date',
        'end_date',
        'status',
        'method',
        'paid_at',
        'notes',
        'cash_register_id',
    ];

    protected $casts = [
        'billing_month' => 'date:Y-m-01',
        'start_date' => 'date',
        'end_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainingPackage()
    {
        return $this->belongsTo(TrainingPackage::class, 'package_id');
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}

