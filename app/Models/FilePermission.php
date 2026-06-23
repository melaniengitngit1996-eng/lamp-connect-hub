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
}
