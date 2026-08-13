<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    protected $fillable = [
        'path',
        'is_cover',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
