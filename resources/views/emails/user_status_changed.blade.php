<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Account Status Changed</title>
</head>
<body>
    <h2>Hello {{ $user->name }},</h2>

    <p>Your account has been <strong>{{ $status }}</strong> by the admin.</p>

    @if($status === 'activated')
        <p>You can now log in and access the system.</p>
    @else
        <p>You will not be able to access the system until your account is activated again.</p>
    @endif

    <p>Regards,<br>{{ config('app.name') }}</p>
</body>
</html>
