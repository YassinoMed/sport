<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltNoteSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'belt_note_category_id',
        'parent_id',
        'name',
        'position',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('position')->orderBy('id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with(['childrenRecursive', 'notes']);
    }

    public function category()
    {
        return $this->belongsTo(BeltNoteCategory::class, 'belt_note_category_id');
    }

    public function notes()
    {
        return $this->morphMany(BeltNoteItem::class, 'noteable')->orderBy('position')->orderBy('id');
    }
}
