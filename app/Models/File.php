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
        'path_human',
        'path_folders'
    ];

    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_SHARED = 'shared';
    const VISIBILITY_PUBLIC = 'public';

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

    public function getPathHumanAttribute()
    {
        $path = [];

        $folder = $this->folder;

        while ($folder) {
            array_unshift($path, $folder->name);

            $folder = $folder->parent;
        }

        return empty($path)
            ? 'Drive'
            : implode(' / ', $path);
    }

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getPathFoldersAttribute()
    {
        $path = [];

        $folder = $this->folder;

        while ($folder) {
            array_unshift($path, [
                'id' => $folder->id,
                'name' => $folder->name,
            ]);

            $folder = $folder->parent;
        }

        return $path;
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function folder()
    {
        return $this->belongsTo(FileFolder::class);
    }

    public function inheritedPermissionFor(User $user)
    {
        if (! $this->folder) {
            return null;
        }

        return $this->folder->inheritedPermissionFor($user);
    }

    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($query) use ($user) {

            $query->where('visibility', 'public')

                ->orWhere('uploaded_by', $user->id)

                ->orWhereHas('folder.permissions', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        });
    }

    public function canView(User $user): bool
    {
        if ($this->visibility === self::VISIBILITY_PUBLIC) {
            return true;
        }

        if ($this->uploaded_by === $user->id) {
            return true;
        }

        return (bool) $this->inheritedPermissionFor($user);
    }

    public function canEdit(User $user): bool
    {
        if ($this->uploaded_by === $user->id) {
            return true;
        }

        $permission = $this->inheritedPermissionFor($user);

        return $permission?->role === 'editor';
    }
}
