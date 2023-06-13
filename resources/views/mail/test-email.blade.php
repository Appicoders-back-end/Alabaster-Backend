@component('mail::message')
    # Forget Password Email

    Dear User,

    Please use the below code to reset your password.

    If you did not create an account, no further action is required.

    Thanks
    {{ config('app.name') }}
@endcomponent
