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
        'path_human',
        'path_folders',
    ];

    const VISIBILITY_PRIVATE = 'private';
    const VISIBILITY_SHARED = 'shared';
    const VISIBILITY_PUBLIC = 'public';

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

    public function getPathFoldersAttribute()
    {
        $path = [];

        $folder = $this;

        while ($folder) {
            array_unshift($path, [
                'id' => $folder->id,
                'name' => $folder->name,
            ]);

            $folder = $folder->parent;
        }

        return $path;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function children()
    {
        return $this->hasMany(FileFolder::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'folder_id');
    }

    public function parent()
    {
        return $this->belongsTo(
            FileFolder::class,
            'parent_id'
        );
    }

    public function getPathHumanAttribute()
    {
        $path = [];

        $folder = $this->parent;

        while ($folder) {
            array_unshift($path, $folder->name);

            $folder = $folder->parent;
        }

        return empty($path)
            ? 'Drive'
            : 'Drive / ' . implode(' / ', $path);
    }

    public function permissions()
    {
        return $this->hasMany(
            FolderPermission::class,
            'folder_id'
        );
    }

    public function scopeVisibleTo($query, User $user)
    {
        return $query->where(function ($query) use ($user) {

            $query->where('visibility', 'public')

                ->orWhere('owner_id', $user->id)

                ->orWhereHas('permissions', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });
        });
    }

    public function inheritedPermissionFor(User $user)
    {
        $folder = $this;

        while ($folder) {

            $permission = $folder->permissions()
                ->where('user_id', $user->id)
                ->first();

            if ($permission) {
                return $permission;
            }

            $folder = $folder->parent;
        }

        return null;
    }
}
