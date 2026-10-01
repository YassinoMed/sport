<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'file_path',
        'mime_type',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}