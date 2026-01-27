<?php

namespace App\Http\Controllers\Ropa;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    /**
     * Display activity logs (HTML view)
     */
   public function index(Request $request)
{
    $query = UserActivity::with('user')->latest();
    $currentUser = auth()->user();

    // Restrict non-admins to their own activities
    if ($currentUser->isAdmin() === false) {
        $query->where('user_id', $currentUser->id);
    }

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('model_type', 'like', "%{$search}%")
              ->orWhere('ip_address', 'like', "%{$search}%")
              ->orWhereRaw("JSON_EXTRACT(meta, '$.description') LIKE ?", ["%{$search}%"]);
        });
    }

    // Action filter
    if ($request->filled('action')) {
        $query->where('action', $request->action);
    }

    // Date range filter
    if ($request->filled('date_range')) {
        $range = $request->date_range;
        switch ($range) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'yesterday':
                $query->whereDate('created_at', today()->subDay());
                break;
            case '7days':
                $query->where('created_at', '>=', now()->subDays(7));
                break;
            case '30days':
                $query->where('created_at', '>=', now()->subDays(30));
                break;
            case '90days':
                $query->where('created_at', '>=', now()->subDays(90));
                break;
        }
    }

    $activities = $query->paginate(15);

    return view('logs.index', compact('activities'));
}

    /**
     * JSON endpoint for AJAX requests
     */
    public function jsonIndex(Request $request)
    {
        $user = auth()->user();
        $query = UserActivity::with('user')->latest();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        if ($request->filled('days')) {
            $query->where('created_at', '>=', now()->subDays((int)$request->days));
        }

        $activities = $query->paginate(50)->withQueryString();

        // Return JSON in a standard structure
        return response()->json([
            'data' => $activities->items(),
            'current_page' => $activities->currentPage(),
            'last_page' => $activities->lastPage(),
            'per_page' => $activities->perPage(),
            'total' => $activities->total(),
            'from' => $activities->firstItem(),
            'to' => $activities->lastItem(),
        ]);
    }

    /**
     * Show a single activity
     */
    public function show(UserActivity $activity)
    {
        $this->authorizeActivity($activity);

        $activity->load('user');

        return response()->json($activity);
    }

    /**
     * Get activities for a specific user (API)
     */
    public function userActivities(int $userId)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $user->id !== $userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $activities = UserActivity::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(50);

        return response()->json([
            'data' => $activities->items(),
            'current_page' => $activities->currentPage(),
            'last_page' => $activities->lastPage(),
            'per_page' => $activities->perPage(),
            'total' => $activities->total(),
            'from' => $activities->firstItem(),
            'to' => $activities->lastItem(),
        ]);
    }

    /**
     * Get activities for a specific model instance
     */
    public function modelActivities(string $model, int $modelId)
    {
        $activities = UserActivity::with('user')
            ->where('model', $model)
            ->where('model_id', $modelId)
            ->latest()
            ->get();

        return response()->json($activities);
    }

    /**
     * Simple authorization helper
     */
    protected function authorizeActivity(UserActivity $activity): void
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $activity->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
    }
}
