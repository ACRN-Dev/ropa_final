<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Two-Factor Authentication Status</title>
</head>
<body>
    <p>Hello {{ $user->name }},</p>

    <p>Your account has {{ $enabled ? 'enabled' : 'disabled' }} Two-Factor Authentication (2FA).</p>

    <p>If you did not make this change, please contact support immediately.</p>

    <p>Thank you,<br>ACRN Data Protection Team</p>
</body>
</html>
