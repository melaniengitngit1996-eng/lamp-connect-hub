@component('mail::message')

# New Message

You have a new message from **{{ $message->sender->name }}**.

@if($message->conversation->type === 'group')
    **Group:** {{ $message->conversation->name }}
@endif

@component('mail::button', ['url' => url('/chat')])
Open Chat
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent