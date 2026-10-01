<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFolderFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_name',
        'folder_slug',
        'original_name',
        'file_path',
        'mime_type',
        'size_bytes',
        'uploaded_by',
    ];
}

