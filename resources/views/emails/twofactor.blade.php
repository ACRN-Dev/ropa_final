<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Two-Factor Code</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background-color: #fff7f0;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 20px;
        }

        h1 {
            color: #ff8800;
        }

        .code {
            display: inline-block;
            background: #ff8800;
            color: #fff;
            font-size: 28px;
            letter-spacing: 4px;
            padding: 12px 24px;
            border-radius: 8px;
            margin: 20px 0;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        footer {
            background-color: #ff8800;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- ACRN Logo -->
        <img src="{{ $message->embed(public_path('logo.jpg')) }}" alt="ACRN Logo" class="logo">

        <h1>Two-Factor Authentication Code</h1>

        <p>Hello <strong>{{ $user->name }}</strong>,</p>

        <p>Please use the verification code below to complete your login:</p>

        <div class="code">{{ $code }}</div>

        <p>This code will expire in 30 minutes. If you did not request this code, please ignore this email.</p>

        <p>Thank you,<br><strong>ACRN Data Protection Team</strong></p>
    </div>

    <footer>
        &copy; {{ date('Y') }} ACRN Data Protection | support@acrn.com
    </footer>
</body>
</html>
