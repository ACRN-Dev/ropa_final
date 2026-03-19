<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnterpriseRisk;
use Illuminate\Http\Request;

class RiskBucketController extends Controller
{
    /**
     * Display the risk buckets dashboard.
     */
    public function index()
    {
        return view('risk.index');
    }

    /**
     * Update a risk's level (called via AJAX from the bucket drag-drop).
     * Accepts { risk_level: 'low'|'medium'|'high'|'critical', _partial: true }
     */
    public function updateLevel(Request $request, EnterpriseRisk $riskRegister)
    {
        $request->validate([
            'risk_level' => 'required|in:low,medium,high,critical',
        ]);

        $riskRegister->update([
            'risk_level' => $request->risk_level,
        ]);

        return response()->json([
            'success'    => true,
            'id'         => $riskRegister->id,
            'risk_level' => $riskRegister->risk_level,
        ]);
    }
}