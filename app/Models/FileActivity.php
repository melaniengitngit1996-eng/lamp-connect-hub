<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FileActivity extends Model
{
    protected $fillable = [
        'file_id',
        'user_id',
        'action',
        'ip_address',
        'user_agent',
    ];

    protected $appends = [
        'created_human',
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function createdHuman(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->created_at?->diffForHumans();
            },
        );
    }
}
