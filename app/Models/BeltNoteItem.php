<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltNoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'position',
    ];

    public function noteable()
    {
        return $this->morphTo();
    }
}

