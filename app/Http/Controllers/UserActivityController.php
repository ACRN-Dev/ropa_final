<?php

namespace App\Http\Controllers;

use App\Models\UserActivity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display all activities
    public function index(Request $request)
    {
        $query = UserActivity::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', "%{$request->search}%")
                    ->orWhere('ip_address', 'like', "%{$request->search}%")
                    ->orWhere('user_agent', 'like', "%{$request->search}%");
            });
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admindashboard.logs.index', compact('activities'));
    }

    // Show a single activity
    public function show(UserActivity $activity)
    {
        return view('admindashboard.logs.show', compact('activity'));
    }

    // Delete an activity
    public function destroy(UserActivity $activity)
    {
        $activity->delete();
        return redirect()->route('admindashboard.logs.index')->with('success', 'Activity deleted successfully.');
    }

    // Export activities to CSV
    public function export()
    {
        $filename = 'user_activities_' . now()->format('Ymd_His') . '.csv';
        $activities = UserActivity::with('user')->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($activities) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'User', 'Action', 'Model', 'Model ID', 'IP Address', 'Created At']);
            foreach ($activities as $activity) {
                fputcsv($handle, [
                    $activity->id,
                    optional($activity->user)->name,
                    $activity->action,
                    $activity->model_type,
                    $activity->model_id,
                    $activity->ip_address,
                    $activity->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, Response::HTTP_OK, $headers);
    }
}
