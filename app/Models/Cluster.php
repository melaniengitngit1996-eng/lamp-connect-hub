<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $table = 'clusters';

    protected $fillable = [
        'local_church_id',
        'name',
        'description',
    ];

    public function localChurch()
    {
        return $this->belongsTo(LocalChurch::class);
    }
}
