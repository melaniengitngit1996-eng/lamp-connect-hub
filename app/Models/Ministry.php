<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    public function localChurch()
    {
        return $this->belongsTo(LocalChurch::class);
    }
}
