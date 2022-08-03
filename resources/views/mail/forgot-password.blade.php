@component('mail::message')
    # Forget Password Email

    Dear {{ $name }},

    Please use the below code to reset your password.

    Verification code is {{ $code }}

    If you did not create an account, no further action is required.

    Thanks
    {{ config('app.name') }}
@endcomponent