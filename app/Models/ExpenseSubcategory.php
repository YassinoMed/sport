<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_category_id',
        'name',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}

