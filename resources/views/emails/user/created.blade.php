@component('mail::message')
# Welcome to {{ config('app.name') }}

Hello **{{ $user->name }}**,  

Your account has been successfully created.  
Here are your login details:

- **Email:** {{ $user->email }}
- **Temporary Password:** {{ $plainPassword }}

@component('mail::button', ['url' => url('/login')])
Login Now
@endcomponent

> For security reasons, please log in and change your password immediately.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
