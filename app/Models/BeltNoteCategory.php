<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltNoteCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'belt_name',
        'name',
        'position',
    ];

    public function subcategories()
    {
        return $this->hasMany(BeltNoteSubcategory::class)
            ->whereNull('parent_id')
            ->orderBy('position')
            ->orderBy('id');
    }

    public function notes()
    {
        return $this->morphMany(BeltNoteItem::class, 'noteable')->orderBy('position')->orderBy('id');
    }
}
