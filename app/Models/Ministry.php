<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    protected $fillable = [
        'name',
        'description',
        'local_church_id'
    ];

    public function localChurch()
    {
        return $this->belongsTo(LocalChurch::class);
    }
}
