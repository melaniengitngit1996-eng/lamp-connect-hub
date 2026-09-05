<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilePermission extends Model
{
    protected $fillable = [
        'file_id',
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

    public function file()
    {
        return $this->belongsTo(File::class);
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

    public function getPrincipalAttribute()
    {
        return match ($this->principal_type) {
            'user' => User::find($this->principal_id),
            'church' => LocalChurch::find($this->principal_id),
            'cluster' => Cluster::find($this->principal_id),
            'ministry' => Ministry::find($this->principal_id),
            'role' => Role::find($this->principal_id),
            default => null,
        };
    }
}
