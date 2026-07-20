@component('mail::message')
# Welcome to LAMP Church Portal

Hello {{ $invitation->full_name }},

You have been invited to create your account for **LAMP Church Portal**.

**Local Church:**  
{{ $invitation->local_church }}

@component('mail::button', ['url' => $signupUrl])
Create Account
@endcomponent

If the button above does not work, copy and paste the following link into your browser:

{{ $signupUrl }}

This invitation link will expire in **7 days**.

If you did not expect this invitation, you may safely ignore this email.

Thanks,<br>
LAMP Church Portal Team
@endcomponent