<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Ropa;
use App\Mail\UserStatusChanged;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function admin()
    {
        $user = auth()->user();
        return view('admindashboard.dashboard', compact('user'));
    }

    /**
     * Show user dashboard.
     */
    public function user()
    {
        $user = auth()->user();
        return view('dashboard', compact('user'));
    }

    /**
     * Show profile edit form.
     */
    public function edit()
    {
        $user = auth()->user();
        return view('admin.edit', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update user fields
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }



    /**
     * Show all users for admin (User Management).
     */
   public function adminUsersIndex(Request $request)
{
    // Capture the search input
    $search = $request->input('search');

    // Query users with optional search
    $users = User::query()
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->paginate(10);

    return view('admindashboard.user.index', compact('users', 'search'));
}



public function editUser($id)
{
    $user = User::findOrFail($id);
    return view('admindashboard.user.edit', compact('user'));
}

public function updateUser(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'user_type' => 'required|in:0,1',
        'department' => 'nullable|string|max:255',
        'job_title' => 'nullable|string|max:255',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only(['name', 'email', 'user_type', 'department', 'job_title']));

    return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
}



public function toggleStatus(User $user)
{
    // Prevent admin from changing their own status
    if (auth()->id() === $user->id) {
        return redirect()->route('admin.users.index')
                         ->with('error', 'You cannot change your own account status.');
    }

    try {
        // Toggle active status
        $user->active = !$user->active;
        $user->save();

        $status = $user->active ? 'activated' : 'deactivated';

        // Log the status change
        Log::info("User status toggled.", [
            'changed_user_id' => $user->id,
            'changed_user_email' => $user->email,
            'new_status' => $status,
            'changed_by_admin_id' => auth()->id(),
        ]);

        // Send email notification using SendGrid
        try {
            Mail::mailer('sendgrid')->to($user->email)->send(new UserStatusChanged($user, $status));
            Log::info("UserStatusChanged email sent successfully.", [
                'recipient' => $user->email,
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send UserStatusChanged email.", [
                'recipient' => $user->email,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.users.index')
                         ->with('success', "User account {$status} successfully.");

    } catch (\Exception $e) {
        // Log failure details
        Log::error("Failed to toggle user status.", [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return redirect()->route('admin.users.index')
                         ->with('error', 'Failed to change user status. Check logs for details.');
    }
}



public function show(User $user)
{
    return view('admindashboard.user.show', compact('user'));
}

public function analytics(Request $request)
{
    $ropas = Ropa::with('user')
        ->when($request->department, fn($q) => $q->where('department_name', $request->department))
        ->when($request->month, fn($q) => 
            $q->whereMonth('date_submitted', date('m', strtotime($request->month)))
        )
        ->orderBy('date_submitted', 'desc')
        ->paginate(10);

    $totalRecords = Ropa::count();
    $highRisk = 25; // Replace with actual calculated values
    $mediumRisk = 40;
    $lowRisk = 35;

    // ✅ Count reviewed vs pending
    $reviewedCount = Ropa::where('status', 'Reviewed')->count();
    $pendingCount = Ropa::where('status', 'Pending')->count();

    // ✅ Make sure to pass these to the view
    return view('admindashboard.analytics.index', compact(
        'ropas',
        'totalRecords',
        'highRisk',
        'mediumRisk',
        'lowRisk',
        'reviewedCount',
        'pendingCount'
    ));
}


public function createUser()
{
    return view('admindashboard.user.create');
}

/**
 * Store a newly created user.
 */




public function store(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'department' => 'nullable|string|max:255',
        'job_title' => 'nullable|string|max:255',
        'user_type' => 'required|in:0,1',
        'password' => 'required|string|min:8|confirmed', // Minimum 8 chars
    ]);

    // Log start of user creation
    Log::info('User creation initiated.', [
        'admin_id' => auth()->id(),
        'admin_email' => auth()->user()->email ?? 'unknown',
        'attempted_user_email' => $validated['email']
    ]);

    try {
        // Store plain password temporarily for email
        $plainPassword = $validated['password'];

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'department' => $validated['department'] ?? null,
            'job_title' => $validated['job_title'] ?? null,
            'user_type' => $validated['user_type'],
            'password' => Hash::make($validated['password']),
        ]);

        // Log user creation success
        Log::info('New user created successfully.', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'created_by_admin_id' => auth()->id()
        ]);

        // Send email via SendGrid
        try {
            Mail::to($user->email)->send(new UserAccountCreated($user, $plainPassword));
            Log::info('UserAccountCreated email sent successfully.', [
                'recipient' => $user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send UserAccountCreated email.', [
                'recipient' => $user->email,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('admin.users.index')
                         ->with('success', 'User created successfully and email notification sent.');

    } catch (\Exception $e) {
        // Log creation failure
        Log::error('User creation failed.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Failed to create user. Check logs for details.');
    }
}


}
