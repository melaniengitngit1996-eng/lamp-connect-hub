<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewChatMessage extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Message $message
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $conversation = $this->message->conversation;
        $sender = $this->message->sender;

        $conversationName = $conversation->type === 'direct'
            ? $sender->name
            : $conversation->name;

        return (new MailMessage)
            ->subject("New message from {$sender->name}")
            ->view('emails.chat.new-message', [
                'message' => $this->message,
                'sender' => $sender,
                'conversation' => $conversation,
                'conversationName' => $conversationName,
            ]);
    }
}
