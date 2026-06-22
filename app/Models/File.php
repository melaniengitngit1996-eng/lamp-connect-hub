<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = [
        'folder_id',
        'name',
        'original_name',
        'mime_type',
        'extension',
        'disk',
        'path',
        'uploaded_by',
        'folder_id',
        'uploaded_by',
        'mime_type',
        'size'
    ];

    protected $appends = [
        'size_human',
        'created_human',
        'url',
    ];

    public function getSizeHumanAttribute()
    {
        $bytes = $this->size;

        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 1) . ' GB';
        }

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }

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

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
