<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ConversationMember extends Pivot
{
    protected $table = 'conversation_members';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'last_read_message_id',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastReadMessage()
    {
        return $this->belongsTo(Message::class, 'last_read_message_id');
    }
}
