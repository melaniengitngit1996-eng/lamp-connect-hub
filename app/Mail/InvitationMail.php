<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation,
        public string $signupUrl,
    ) {}

    public function build()
    {
        return $this
            ->subject('You have been invited to LAMP Church Connect')
            ->markdown('emails.invitation');
    }
}
