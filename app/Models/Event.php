<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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

    protected $appends = ['cover_image_url'];

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

    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image
            ? Storage::url($this->cover_image)
            : null;
    }

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }
}
