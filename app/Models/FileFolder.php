<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileFolder extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'parent_id'
    ];

    protected $appends = [
        'created_human',
    ];

    public function getCreatedHumanAttribute()
    {
        if (! $this->created_at) {
            return null;
        }

        if ($this->created_at->gt(now()->subMinute())) {
            return 'just now';
        }

        return $this->created_at->diffForHumans();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function children()
    {
        return $this->hasMany(FileFolder::class, 'parent_id');
    }
}
