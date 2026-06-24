<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\FolderPermission;
use App\Models\FilePermission;
use App\Models\User;

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
        'size',
        'visibility',
        'share_token'
    ];

    protected $appends = [
        'size_human',
        'created_human',
        'url',
        'path_human',
        'path_folders'
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
            // File itself is public
            $query->where('visibility', 'public')

                // Parent folder is public
                ->orWhereHas('folder', function ($query) {
                    $query->where('visibility', 'public');
                })

                // Owner
                ->orWhere('uploaded_by', $user->id)

                // Explicit file permission
                ->orWhereHas('permissions', function ($query) use ($user) {
                    $query->matchesUser($user);
                })

                // Inherited folder permission
                ->orWhereHas('folder.permissions', function ($query) use ($user) {
                    $query->matchesUser($user);
                });
        });
    }

    public function canView(User $user): bool
    {
        if ($this->visibility === 'public') {
            return true;
        }

        return $this->roleFor($user) !== null;
    }

    public function canEdit(User $user): bool
    {
        return in_array(
            $this->roleFor($user),
            [FolderPermission::CONTRIBUTOR, FolderPermission::MANAGER]
        );
    }

    public function canManage(User $user): bool
    {
        return $this->roleFor($user) === FolderPermission::MANAGER;
    }

    public function roleFor(User $user): ?string
    {
        if ($this->uploaded_by === $user->id) {
            return FolderPermission::MANAGER;
        }

        if (!$this->folder) {
            return null;
        }

        return $this->folder->roleFor($user);
    }

    public function permissions()
    {
        return $this->hasMany(
            FilePermission::class,
            'file_id'
        );
    }
}
