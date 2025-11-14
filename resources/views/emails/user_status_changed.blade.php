<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Account Status Changed</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            background: #fff;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2b2b2b;
            text-align: center;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        strong.status {
            color: {{ $status === 'activated' ? '#28a745' : '#dc3545' }};
            text-transform: capitalize;
        }

        footer {
            background-color: #ffcc00; /* Yellow footer */
            color: #222;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
            border-top: 2px solid #e6b800;
            font-weight: 600;
        }

        footer a {
            color: #222;
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Status Update</h2>

        <p>Hello <strong>{{ $user->name }}</strong>,</p>

        <p>Your account has been 
            <strong class="status">{{ $status }}</strong> 
            by the admin.
        </p>

        @if($status === 'activated')
            <p>You can now log in and access the system.</p>
        @else
            <p>You will not be able to access the system until your account is activated again.</p>
        @endif

        <p>Regards,<br><strong>{{ config('app.name') }}</strong></p>
    </div>

    <footer>
        &copy; {{ date('Y') }} {{ config('app.name') }} | 
        <a href="mailto:support@acrn.com">support@acrn.com</a>
    </footer>
</body>
</html>
