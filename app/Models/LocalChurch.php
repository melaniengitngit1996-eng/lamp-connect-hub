<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalChurch extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    public function ministries()
    {
        return $this->hasMany(Ministry::class);
    }

    public function clusters()
    {
        return $this->hasMany(Cluster::class);
    }
}
