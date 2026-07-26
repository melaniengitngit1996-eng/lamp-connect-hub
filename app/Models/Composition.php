<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Number;

class Composition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'is_featured',
        'status',
        'downloads',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'file_size' => 'integer',
        'downloads' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    protected function fileSizeFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => Number::fileSize($this->file_size)
        );
    }

    protected function publishedAtFormatted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->published_at?->diffForHumans()
        );
    }
}
