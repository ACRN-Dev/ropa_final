<x-guest-layout>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Overpass:wght@300;400;600;700&display=swap');
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f5f6fa;
        font-family: 'Overpass', sans-serif;
        color: #3d3d3d;
        padding: 20px 0;
    }

    .outer {
        width: 100%;
        max-width: 500px;
        padding: 20px;
    }

    .top {
        text-align: center;
        margin-bottom: 28px;
    }
    .top img {
        max-width: 210px;
        height: auto;
        margin-bottom: 22px;
    }
    .top h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 5px;
    }
    .top p {
        font-size: 0.88rem;
        color: #9a9a9a;
    }

    .card {
        background: #fff;
        border: 1px solid #e2e5ee;
        border-radius: 8px;
        padding: 34px 32px 28px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .form-group { margin-bottom: 14px; }
    .form-group label {
        display: block;
        font-size: 0.83rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
    }
    .form-control {
        width: 100%;
        padding: 10px 13px;
        border: 1px solid #d5d8e0;
        border-radius: 5px;
        font-family: 'Overpass', sans-serif;
        font-size: 0.9rem;
        color: #1a1a2e;
        background: #f8f9fc;
        outline: none;
        transition: border-color 0.15s;
    }
    .form-control:focus {
        border-color: #5a5fcf;
        background: #fff;
        box-shadow: none;
    }
    .form-control::placeholder { color: #c0c3ce; }

    .field-error { font-size: 0.7rem; color: #dc2626; margin-top: 4px; }

    .btn-primary {
        display: block;
        width: 100%;
        padding: 12px;
        background: #5a5fcf;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-family: 'Overpass', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
        margin-top: 4px;
    }
    .btn-primary:hover { background: #4a4fbf; }

    .card-footer {
        text-align: center;
        font-size: 0.75rem;
        color: #aaa;
        margin-top: 18px;
        padding-top: 14px;
        border-top: 1px solid #f0f0f5;
    }
    .card-footer a { color: #5a5fcf; text-decoration: none; }
    .card-footer a:hover { text-decoration: underline; }
</style>

<div class="outer">
    <div class="top">
        <img src="{{ asset('logo.jpg') }}" alt="ACRN Logo">
        <h2>Create an account</h2>
        <p>Register for your ACRN ROPA account</p>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="name">Full name</label>
                <input type="text" name="name" id="name" class="form-control"
                    value="{{ old('name') }}" placeholder="Your full name"
                    required autofocus autocomplete="name">
                @error('name')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email') }}" placeholder=""
                    required autocomplete="username">
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder=""
                    required autocomplete="new-password">
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                    placeholder=""
                    required autocomplete="new-password">
                @error('password_confirmation')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-primary">Create account</button>
        </form>

        <div class="card-footer">
            Already have an account? <a href="{{ route('login') }}">Sign in</a>
        </div>
    </div>
</div>
</x-guest-layout>