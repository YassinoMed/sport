<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function subcategories()
    {
        return $this->hasMany(ExpenseSubcategory::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}

