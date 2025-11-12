<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    public function show()
    {
        return view('auth.2fa-verify');
    }


    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'two_factor_code' => 'required|digits:4',
        ]);

        $user = User::where('email', $request->email)
                    ->where('two_factor_code', $request->two_factor_code)
                    ->first();

        if (!$user) {
            return back()->withErrors(['two_factor_code' => 'Invalid code.']);
        }

        if ($user->two_factor_expires_at->lt(now())) {
            return back()->withErrors(['two_factor_code' => 'Code has expired.']);
        }

        // Clear 2FA code
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        Auth::login($user);

        return $user->isAdmin() 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('user.dashboard');
    }


    public function resend(Request $request)
{
    $email = session('email');

    if (!$email) {
        return redirect()->route('login')->withErrors(['email' => 'Session expired. Please log in again.']);
    }

    $user = \App\Models\User::where('email', $email)->first();

    if (!$user) {
        return redirect()->route('login')->withErrors(['email' => 'User not found.']);
    }

    // Generate a new code
    $user->two_factor_code = rand(1000, 9999);
    $user->two_factor_expires_at = now()->addMinutes(30);
    $user->save();

    // Send new code via email
    $user->notify(new \App\Notifications\TwoFactorCode($user->two_factor_code));

    return back()->with('status', 'A new 2FA code has been sent to your email.');
}

}
