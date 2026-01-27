<?php

namespace App\Http\Controllers;
namespace App\Traits;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UserActivityController;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA verification form
     */
    public function show()
    {
        return view('auth.2fa-verify');
    }

    /**
     * Verify the entered 2FA code
     */
    public function verify(Request $request)
    {
        try {
            // 🚨 Prevent infinite redirect loop if email is missing
            if (!$request->email) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your session has expired. Please log in again.']);
            }

            // Validate input
            $request->validate([
                'email' => 'required|email',
                'two_factor_code' => 'required|digits:4',
            ]);

            // Fetch user by email & code
            $user = User::where('email', $request->email)
                        ->where('two_factor_code', $request->two_factor_code)
                        ->first();

            if (!$user) {
                return back()->withErrors([
                    'two_factor_code' => 'Invalid verification code.'
                ]);
            }

            // Check expiry
            if ($user->two_factor_expires_at && $user->two_factor_expires_at->lt(now())) {
                return back()->withErrors([
                    'two_factor_code' => 'The verification code has expired.'
                ]);
            }

            // Clear code
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            // Log user in
            Auth::login($user);

            // Redirect based on role
            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');

        } catch (\Exception $e) {
            Log::error('2FA Verification Error: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Something went wrong during verification. Please try again.'
            ]);
        }
    }

    /**
     * Resend 2FA code
     */
    public function resend(Request $request)
    {
        try {
            $email = session('email');

            // No email in session → session expired
            if (!$email) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Your session has expired. Please log in again.']);
            }

            // Fetch user
            $user = User::where('email', $email)->first();

            if (!$user) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'User not found.']);
            }

            // Generate new code
            $user->two_factor_code = rand(1000, 9999);
            $user->two_factor_expires_at = now()->addMinutes(30);
            $user->save();

            // Send via email
            $user->notify(new \App\Notifications\TwoFactorCode($user->two_factor_code));

            return back()->with('status', 'A new verification code has been sent to your email.');

        } catch (\Exception $e) {
            Log::error('2FA Resend Error: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'Unable to resend the verification code at the moment.'
            ]);
        }
    }



    
}
