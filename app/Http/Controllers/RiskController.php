<?php

namespace App\Http\Controllers;

use App\Models\Risk;
use Illuminate\Http\Request;
use App\Models\EnterpriseRisk;
use Illuminate\Support\Facades\Auth;

class RiskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = EnterpriseRisk::with('owner');

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('risk_id', 'like', '%' . $request->search . '%');
            });
        }

        // Risk level filter
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->risk_level);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get paginated risks
        $risks = $query->latest()->paginate(15);

        // Statistics
        $totalRisks = EnterpriseRisk::count();
        $highRisks = EnterpriseRisk::whereIn('risk_level', ['high', 'critical'])->count();
        $mediumRisks = EnterpriseRisk::where('risk_level', 'medium')->count();
        $lowRisks = EnterpriseRisk::where('risk_level', 'low')->count();

        return view('risk.index', compact(
            'risks',
            'totalRisks',
            'highRisks',
            'mediumRisks',
            'lowRisks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('risk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'risk_category' => 'required|string',
            'risk_level' => 'required|in:low,medium,high,critical',
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'inherent_risk_score' => 'nullable|integer',
            'current_controls' => 'nullable|string',
            'residual_risk_score' => 'nullable|integer',
            'mitigation_plan' => 'nullable|string',
            'status' => 'required|in:open,in_progress,mitigated,closed',
            'target_date' => 'nullable|date',
            'review_date' => 'nullable|date',
        ]);

        // Generate Risk ID
        $validated['risk_id'] = ' Risk-' . date('Y') . '-' . str_pad(EnterpriseRisk::count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Set owner to current user
        $validated['owner_id'] = Auth::id();

        // Calculate inherent risk score if not provided
        if (!isset($validated['inherent_risk_score'])) {
            $validated['inherent_risk_score'] = $validated['likelihood'] * $validated['impact'];
        }

        EnterpriseRisk::create($validated);

        return redirect()->route('risk-register.index')
            ->with('success', 'Risk has been successfully added to the register.');
    }

    /**
     * Display the specified resource.
     */
   // RiskController.php
public function show($id)
{
    $risk = EnterpriseRisk::findOrFail($id); // this ensures $risk exists
    return view('risk.show', compact('risk'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnterpriseRisk $risk)
    {
        return view('risk-register.edit', compact('risk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EnterpriseRisk $risk)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'risk_category' => 'required|string',
            'risk_level' => 'required|in:low,medium,high,critical',
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'inherent_risk_score' => 'nullable|integer',
            'current_controls' => 'nullable|string',
            'residual_risk_score' => 'nullable|integer',
            'mitigation_plan' => 'nullable|string',
            'status' => 'required|in:open,in_progress,mitigated,closed',
            'target_date' => 'nullable|date',
            'review_date' => 'nullable|date',
        ]);

        // Recalculate inherent risk score if likelihood or impact changed
        if (!isset($validated['inherent_risk_score'])) {
            $validated['inherent_risk_score'] = $validated['likelihood'] * $validated['impact'];
        }

        $risk->update($validated);

        return redirect()->route('risk-register.index')
            ->with('success', 'Risk has been successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
public function destroy(EnterpriseRisk $risk_register)
{
    $risk_register->delete();

    return redirect()->route('risk-register.index')
        ->with('success', 'Risk has been successfully deleted.');
}

}