<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'type',
        'message',
        'file_id',
        'reply_to',
        'edited_at',
    ];

    protected $casts = [
        'message' => 'encrypted',
        'edited_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reply()
    {
        return $this->belongsTo(Message::class, 'reply_to');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to');
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function scopeConversation(Builder $query, Conversation $conversation)
    {
        return $query->where(
            'conversation_id',
            $conversation->id
        );
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest();
    }

    public function scopeOldestFirst(Builder $query): Builder
    {
        return $query->oldest();
    }
}
