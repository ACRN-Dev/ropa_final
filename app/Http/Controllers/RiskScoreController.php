<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use App\Models\Ropa;
use Illuminate\Http\Request;

class RiskScoreController extends Controller
{
    public function index()
    {
        $riskScores = RiskScore::with('ropa')->paginate(10);
        return view('admindashboard.score.index', compact('riskScores'));
    }

    public function create()
    {
        $ropas = Ropa::all();
        return view('admindashboard.risk_scores.create', compact('ropas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ropa_id' => 'required|exists:ropas,id',
            'risk_level' => 'nullable|integer|min:1|max:5',
            'category' => 'nullable|string|max:255',
            'factors_considered' => 'nullable|string',
            'assessed_by' => 'nullable|string|max:255',
            'assessment_date' => 'nullable|date',
        ]);

        RiskScore::create($validated);

        return redirect()->route('risk_scores.index')
            ->with('success', 'Risk score recorded successfully.');
    }
}
