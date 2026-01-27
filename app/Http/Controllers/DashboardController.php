<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserStatusLog;
use App\Models\Ropa;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use App\Mail\UserStatusChanged;
use App\Mail\UserAccountCreated;
use App\Models\EnterpriseRisk;
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

        $allRopas = Ropa::with(['enterpriseRisks', 'user'])
    ->where('user_id', $user->id)
    ->latest()
    ->paginate(15);


        /* -------------------------------
         | Risk distribution (ADMIN)
         ------------------------------- */
        $risks = EnterpriseRisk::all();

        $critical = $risks->where('risk_level', 'critical')->count();
        $high     = $risks->where('risk_level', 'high')->count();
        $medium   = $risks->where('risk_level', 'medium')->count();
        $low      = $risks->where('risk_level', 'low')->count();

        $total = max($critical + $high + $medium + $low, 1);

        $criticalRisk = round(($critical / $total) * 100, 1);
        $highRisk     = round(($high / $total) * 100, 1);
        $mediumRisk   = round(($medium / $total) * 100, 1);
        $lowRisk      = round(($low / $total) * 100, 1);

        /* -------------------------------
         | Admin ROPA stats
         ------------------------------- */
        $overdueReviews = Ropa::where('status', 'Pending')
            ->whereDate('created_at', '<', now()->subDays(30))
            ->count();

        $tasksCompleted = Ropa::whereIn('status', ['Reviewed', 'Approved'])->count();

        return view('admindashboard.dashboard', compact(
            'user',
            'critical',
            'high',
            'medium',
            'low',
            'criticalRisk',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'overdueReviews',
            'tasksCompleted'
        ));
    }


// public function admin()
// {
//     return $this->reviewRiskDashboard();
// }


    /**
 * Show the form for creating a new user.
 */
public function createUser()
{
    return view('admindashboard.user.create');
}


    

    /**
     * Show user dashboard.
     */
  public function user()
{
    return $this->dashboard();
}

public function dashboard()
    {
        $user = auth()->user();

        /* ===============================
         |  Notifications
         =============================== */
        $notifications = UserStatusLog::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        /* ===============================
         |  Recent & Archived ROPAs
         =============================== */
        $recentRopas = Ropa::with('user')
            ->where('user_id', $user->id)
            ->where('archived', false)
            ->latest()
            ->take(5)
            ->get();

        $archivedRopas = Ropa::with('user')
            ->where('user_id', $user->id)
            ->where('archived', true)
            ->latest()
            ->get();

        /* ===============================
         |  All ROPAs (Paginated)
         =============================== */
        $allRopas = Ropa::with(['enterpriseRisks', 'user'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15, ['*'], 'ropa_page');

        /* ===============================
         |  All Enterprise Risks
         =============================== */
        $allRisks = EnterpriseRisk::with('owner')
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere(function ($q) use ($user) {
                        $q->where('source_type', 'ROPA')
                          ->whereIn('source_id', function ($subq) use ($user) {
                              $subq->select('id')
                                   ->from('ropas')
                                   ->where('user_id', $user->id);
                          });
                    });
            })
            ->latest()
            ->paginate(15, ['*'], 'risks_page');

        /* ===============================
         |  Risk Collections
         =============================== */
        $risks = EnterpriseRisk::where('owner_id', $user->id)->get();

        $ropaRisks = EnterpriseRisk::where('source_type', 'ROPA')
            ->whereIn('source_id', function ($q) use ($user) {
                $q->select('id')
                  ->from('ropas')
                  ->where('user_id', $user->id);
            })
            ->get();

        $allUserRisks = $risks->merge($ropaRisks)->unique('id');

        /* ===============================
         |  Risk Counts by Level
         =============================== */
        $critical = $allUserRisks->where('risk_level', 'critical')->count();
        $high     = $allUserRisks->where('risk_level', 'high')->count();
        $medium   = $allUserRisks->where('risk_level', 'medium')->count();
        $low      = $allUserRisks->where('risk_level', 'low')->count();

        $total = max($critical + $high + $medium + $low, 1);

        $criticalRisk = round(($critical / $total) * 100, 1);
        $highRisk     = round(($high / $total) * 100, 1);
        $mediumRisk   = round(($medium / $total) * 100, 1);
        $lowRisk      = round(($low / $total) * 100, 1);

        /* ===============================
         |  Risk Status Stats
         =============================== */
        $totalRisks       = $allUserRisks->count();
        $openRisks        = $allUserRisks->where('status', 'open')->count();
        $inProgressRisks  = $allUserRisks->where('status', 'in_progress')->count();
        $mitigatedRisks   = $allUserRisks->where('status', 'mitigated')->count();

        /* ===============================
         |  View
         =============================== */
        return view('dashboard', compact(
            'user',
            'notifications',
            'recentRopas',
            'archivedRopas',
            'allRopas',
            'allRisks',
            'criticalRisk',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'ropaRisks',
            'risks',
            'totalRisks',
            'openRisks',
            'inProgressRisks',
            'mitigatedRisks',
            'critical',
            'high',
            'medium',
            'low'
        ));
    }

    /* =========================================================
     |  USER RISK DASHBOARD (UNCHANGED, SAFE)
     ========================================================= */
    public function userRiskDashboard()
    {
        $user = Auth::user();

        $ownedRisks = EnterpriseRisk::where('owner_id', $user->id)->get();

        $ropaRisks = EnterpriseRisk::where('source_type', 'ROPA')
            ->whereIn('source_id', function ($q) use ($user) {
                $q->select('id')
                  ->from('ropas')
                  ->where('user_id', $user->id);
            })
            ->get();

        $risks = $ownedRisks->merge($ropaRisks)->unique('id');

        $critical = $risks->where('risk_level', 'critical')->count();
        $high     = $risks->where('risk_level', 'high')->count();
        $medium   = $risks->where('risk_level', 'medium')->count();
        $low      = $risks->where('risk_level', 'low')->count();

        $total = max($critical + $high + $medium + $low, 1);

        $criticalRisk = round(($critical / $total) * 100, 1);
        $highRisk     = round(($high / $total) * 100, 1);
        $mediumRisk   = round(($medium / $total) * 100, 1);
        $lowRisk      = round(($low / $total) * 100, 1);

        $totalRisks = $risks->count();
        $openRisks  = $risks->where('status', 'open')->count();
        $highPriorityRisks = $risks->whereIn('risk_level', ['high', 'critical'])->count();

        return view('dashboard', compact(
            'user',
            'criticalRisk',
            'highRisk',
            'mediumRisk',
            'lowRisk',
            'risks',
            'totalRisks',
            'openRisks',
            'highPriorityRisks',
            'critical',
            'high',
            'medium',
            'low'
        ));
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
    // Base query
    $baseQuery = Ropa::query()
        ->with('user:id,name') // load only needed columns
        ->when($request->department, fn($q) =>
            $q->where('department_name', $request->department)
        )
        ->when($request->month, fn($q) =>
            $q->whereMonth('date_submitted', date('m', strtotime($request->month)))
        );

    // Main paginated data
    $ropas = $baseQuery
        ->orderBy('date_submitted', 'desc')
        ->paginate(10);

    // Total records from filtered query (not whole table)
    $totalRecords = (clone $baseQuery)->count();

    // Risk distribution (replace with your real logic)
    $highRisk = 25;
    $mediumRisk = 40;
    $lowRisk = 35;

    // Reviewed vs Pending (single grouped query)
    $statusCounts = Ropa::select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    $reviewedCount = $statusCounts['Reviewed'] ?? 0;
    $pendingCount = $statusCounts['Pending'] ?? 0;

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

public function reviewRiskDashboard()
{
    $user = auth()->user();
    $reviews = Review::all();

    $total = max($reviews->count(), 1);

    $critical = $reviews->filter(fn($r) => $r->total_score <= 50)->count();
    $high     = $reviews->filter(fn($r) => $r->total_score > 50 && $r->total_score <= 100)->count();
    $medium   = $reviews->filter(fn($r) => $r->total_score > 100 && $r->total_score <= 160)->count();
    $low      = $reviews->filter(fn($r) => $r->total_score > 160)->count();

    $criticalRisk = round(($critical / $total) * 100, 1);
    $highRisk     = round(($high / $total) * 100, 1);
    $mediumRisk   = round(($medium / $total) * 100, 1);
    $lowRisk      = round(($low / $total) * 100, 1);

    return view('admindashboard.dashboard', compact(
        'user',
        'criticalRisk',
        'highRisk',
        'mediumRisk',
        'lowRisk',
        'reviews'
    ));
}




}
