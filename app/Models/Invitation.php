<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'member_id',
        'token',
        'full_name',
        'email',
        'local_church',
        'expires_at',
        'accepted_at',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function getStatusAttribute(): string
    {
        if ($this->accepted_at) {
            return 'accepted';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'expired';
        }

        return 'pending';
    }
}
