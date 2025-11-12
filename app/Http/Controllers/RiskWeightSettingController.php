<?php

namespace App\Http\Controllers;

use App\Models\Ropa;
use App\Models\RiskWeightSetting;
use Illuminate\Http\Request;

class RiskWeightSettingController extends Controller
{



    public function index()
   {
    // Fetch all ROPA records, optionally paginate
    $ropas = Ropa::with('riskWeightSettings')->paginate(10);
    return view('admindashboard.score.index', compact('ropas'));
   }



    /**
     * Display and manage risk weights for a specific Ropa record.
     */
    public function edit(Ropa $ropa)
    {
        // Retrieve all weights for this Ropa
        $weights = $ropa->riskWeightSettings()->get();

        // All Ropa fillable fields you want to assign weights to
        $fields = collect($ropa->getFillable())
            ->reject(fn($field) => in_array($field, ['id', 'user_id', 'date_submitted']));

        return view('admindashboard.score.index', compact('ropa', 'weights', 'fields'));
    }

    /**
     * Update or create weights dynamically.
     */
    public function update(Request $request, Ropa $ropa)
    {
        $validated = $request->validate([
            'weights' => 'array',
            'weights.*' => 'numeric|min:0|max:100',
        ]);

        foreach ($validated['weights'] as $field => $weight) {
            RiskWeightSetting::updateOrCreate(
                ['ropa_id' => $ropa->id, 'field_name' => $field],
                ['weight' => $weight]
            );
        }

        return redirect()
            ->route('ropas.weights.edit', $ropa->id)
            ->with('success', 'Risk weights updated successfully!');
    }


   public function show(Ropa $ropa)
{
    // Fetch all risk weight settings for this ROPA record
    // Pluck by field_name => weight for easy access
    $weights = $ropa->riskWeightSettings()->pluck('weight', 'field_name');

    // Define all scoreable fields (for display, even if no weight exists)
    $scoreableFields = [
        'status',
        'other_specify',
        'information_shared',
        'information_nature',
        'outsourced_processing',
        'processor',
        'transborder_processing',
        'country',
        'lawful_basis',
        'retention_period_years',
        'retention_rationale',
        'users_count',
        'access_control',
        'personal_data_category',
    ];

    return view('admindashboard.score.show', compact('ropa', 'weights', 'scoreableFields'));
}


}
