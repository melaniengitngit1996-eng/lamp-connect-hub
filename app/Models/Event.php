<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'starts_at',
        'ends_at',
        'venue',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    protected function startsAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->starts_at?->format('M j, Y g:i A'),
        );
    }

    protected function endsAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->ends_at?->format('M j, Y g:i A'),
        );
    }

    protected function dateRange(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->starts_at) {
                    return null;
                }

                if (! $this->ends_at) {
                    return $this->starts_at->format('M j, Y g:i A');
                }

                return $this->starts_at->format('M j, Y g:i A')
                    . ' - '
                    . $this->ends_at->format('M j, Y g:i A');
            },
        );
    }
}
