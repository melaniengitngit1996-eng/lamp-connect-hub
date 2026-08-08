<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Conversation extends Model
{
    protected $fillable = [
        'type',
        'name',
        'description',
        'created_by',
        'is_private',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(
            User::class,
            'conversation_members'
        )
            ->using(ConversationMember::class)
            ->withPivot([
                'role',
                'last_read_message_id',
                'joined_at',
            ])
            ->withTimestamps();
    }

    public function conversationMembers()
    {
        return $this->hasMany(ConversationMember::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return $query->whereHas('members', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        });
    }

    public function scopeChannels(Builder $query): Builder
    {
        return $query->where('type', 'channel');
    }

    public function scopeGroups(Builder $query): Builder
    {
        return $query->where('type', 'group');
    }

    public function scopeDirect(Builder $query): Builder
    {
        return $query->where('type', 'direct');
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->latest('updated_at');
    }
}
