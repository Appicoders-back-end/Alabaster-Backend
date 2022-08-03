@component('mail::message')
    # Account Created 

    Dear {{ $user->name }},

    Your Alabaster account has been created.
    Use below credentials to login in Mobile App.

    Email: {{$user->email}}
    Password: {{$code}}
    
Thanks
{{ config('app.name') }}
@endcomponent
