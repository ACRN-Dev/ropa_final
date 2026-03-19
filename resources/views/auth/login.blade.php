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
    }

    .outer {
        width: 100%;
        max-width: 500px;
        padding: 20px;
    }

    /* Logo + headings */
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

    /* Card */
    .card {
        background: #fff;
        border: 1px solid #e2e5ee;
        border-radius: 8px;
        padding: 34px 32px 28px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    /* Alert */
    .alert {
        padding: 9px 13px;
        border-radius: 5px;
        font-size: 0.78rem;
        margin-bottom: 16px;
    }
    .alert-success { background: #eaf7ee; border: 1px solid #b6e8c4; color: #276749; }
    .alert-danger  { background: #fdf2f2; border: 1px solid #f5c6cb; color: #9b1c1c; }
    .alert-info    { background: #e8f4fb; border: 1px solid #bee3f8; color: #1a5276; }

    /* Form */
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

    /* Options */
    .options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        font-size: 0.78rem;
    }
    .remember { display: flex; align-items: center; gap: 6px; color: #666; cursor: pointer; }
    .remember input { width: 13px; height: 13px; accent-color: #5a5fcf; }
    .forgot { color: #5a5fcf; text-decoration: none; font-size: 0.78rem; }
    .forgot:hover { text-decoration: underline; }

    /* Button */
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
    }
    .btn-primary:hover { background: #4a4fbf; }

    /* Footer */
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
        <h2>Sign In</h2>
        <p>Sign in to your ACRN ROPA account</p>
    </div>

    <div class="card">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" name="email" id="email" class="form-control"
                    value="{{ old('email') }}" placeholder=""
                    required autofocus autocomplete="username">
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control"
                    placeholder=""
                    required autocomplete="current-password">
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="options">
                <label class="remember">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-primary">Sign in</button>
        </form>

        @if (Route::has('register'))
            <div class="card-footer">
                No account? <a href="{{ route('register') }}">Register here</a>
            </div>
        @endif
    </div>
</div>
</x-guest-layout>