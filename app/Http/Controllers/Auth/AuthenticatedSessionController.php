<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Attempt authentication
        $request->authenticate();

        // Regenerate session
        $request->session()->regenerate();

        $user = Auth::user();

        // 🛑 Check if account is active
        if (!$user->active) {
            Auth::logout();
            return back()
                ->withErrors(['email' => 'Your account is deactivated. Please contact the administrator.'])
                ->withInput();
        }

        // 🔒 Check if 2FA is enabled
        if ($user->two_factor_enabled) {
            // Generate 4-digit code
            $user->two_factor_code = rand(1000, 9999);
            $user->two_factor_expires_at = now()->addMinutes(30);
            $user->save();

            // Send email notification
            $user->notify(new TwoFactorCode($user->two_factor_code));

            // Logout until verification
            Auth::logout();

            // ✅ Persist email in session for verification
            session()->put('email', $user->email);

            // Redirect to 2FA verify view
            return redirect()->route('2fa.verify');
        }

        // Normal login (no 2FA)
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
