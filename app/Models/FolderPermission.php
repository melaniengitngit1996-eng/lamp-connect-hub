<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FolderPermission extends Model
{
    protected $fillable = [
        'folder_id',
        'principal_type',
        'principal_id',
        'role',
    ];

    const USER = 'user';
    const CHURCH = 'church';
    const CLUSTER = 'cluster';
    const MINISTRY = 'ministry';

    const VIEWER = 'viewer';
    const CONTRIBUTOR = 'contributor';
    const MANAGER = 'manager';

    protected $appends = [
        'principal_name',
    ];

    public function getPrincipalNameAttribute()
    {
        return match ($this->principal_type) {
            'user' => User::find($this->principal_id)?->name,
            'church' => LocalChurch::find($this->principal_id)?->name,
            'cluster' => Cluster::find($this->principal_id)?->name,
            'ministry' => Ministry::find($this->principal_id)?->name,
            default => null,
        };
    }

    public function folder()
    {
        return $this->belongsTo(FileFolder::class);
    }

    public static function principalsFor(User $user): array
    {
        $principals = [
            ['type' => 'user', 'id' => $user->id],
        ];

        if ($user->local_church_id) {
            $principals[] = [
                'type' => 'church',
                'id' => $user->local_church_id,
            ];
        }

        foreach ($user->clusters as $cluster) {
            $principals[] = [
                'type' => 'cluster',
                'id' => $cluster->id,
            ];
        }

        foreach ($user->ministries as $ministry) {
            $principals[] = [
                'type' => 'ministry',
                'id' => $ministry->id,
            ];
        }

        return $principals;
    }

    public function scopeMatchesUser($query, User $user)
    {
        $principals = $user->principals();

        $query->where(function ($query) use ($principals) {

            foreach ($principals as $principal) {

                $query->orWhere(function ($query) use ($principal) {

                    $query->where('principal_type', $principal['type'])
                        ->where('principal_id', $principal['id']);
                });
            }
        });
    }
}
