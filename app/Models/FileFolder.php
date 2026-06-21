<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileFolder extends Model
{
    protected $appends = [
        'created_human',
    ];

    public function getCreatedHumanAttribute()
    {
        return $this->created_at?->diffForHumans();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
