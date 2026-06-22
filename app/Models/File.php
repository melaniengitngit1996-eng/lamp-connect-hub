<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'folder_id',
        'name',
        'original_name',
        'mime_type',
        'extension',
        'disk',
        'path',
        'uploaded_by',
        'folder_id',
        'uploaded_by',
        'mime_type',
        'size'
    ];
}
