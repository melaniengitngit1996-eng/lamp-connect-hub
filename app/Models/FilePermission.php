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
}
