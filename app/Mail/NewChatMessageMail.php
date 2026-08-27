<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewChatMessageMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Message $message,
    ) {}

    public function build()
    {
        $this->message->loadMissing([
            'sender',
            'conversation',
        ]);

        return $this
            ->subject('You have a new message on LAMP Church Connect')
            ->markdown('emails.chat.new-message');
    }
}
