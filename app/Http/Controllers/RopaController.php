<?php

namespace App\Http\Controllers;

use App\Models\Ropa;
use App\Models\Review;
use Illuminate\Http\Request;

class RopaController extends Controller
{
    // Require authentication
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource with filtering/searching
     */
   public function index(Request $request)
{
    $search = $request->input('search');
    $department = $request->input('department');
    $month = $request->input('month');

    $ropas = Ropa::with('user')
        ->where('user_id', auth()->id()) // ✅ Only fetch records for logged-in user
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('organisation_name', 'like', "%{$search}%")
                  ->orWhere('department_name', 'like', "%{$search}%")
                  ->orWhere('other_department', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        })
        ->when($department, fn($query, $department) =>
            $query->where('department_name', $department)
        )
        ->when($month, fn($query, $month) =>
            $query->whereMonth('date_submitted', date('m', strtotime($month)))
                  ->whereYear('date_submitted', date('Y', strtotime($month)))
        )
        ->orderBy('date_submitted', 'desc')
        ->paginate(10);

    return view('ropa.index', compact('ropas'));
}

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $lawfulOptions = $this->getLawfulBasisOptions();
        $personalDataOptions = $this->getPersonalDataCategories();
        return view('ropa.create', compact('lawfulOptions', 'personalDataOptions'));
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'status' => 'nullable|string|in:Pending,Reviewed',
            'date_submitted' => 'nullable|date',
            'other_specify' => 'nullable|string|max:255',
            'information_shared' => 'nullable|boolean',
            'information_nature' => 'nullable|string',
            'outsourced_processing' => 'nullable|boolean',
            'processor' => 'nullable|string|max:255',
            'transborder_processing' => 'nullable|boolean',
            'country' => 'nullable|string|max:255',
            'lawful_basis' => 'nullable|array',
            'lawful_basis.*' => 'string',
            'retention_period_years' => 'nullable|integer',
            'retention_rationale' => 'nullable|string',
            'users_count' => 'nullable|integer',
            'access_control' => 'nullable|boolean',
            'personal_data_category' => 'nullable|array',
            'personal_data_category.*' => 'string',
            'organisation_name' => 'nullable|string|max:255',
            'department_name' => 'nullable|string|max:255',
            'other_department' => 'nullable|string|max:255',
            'processes' => 'nullable|array',
            'processes.*' => 'string',
            'data_sources' => 'nullable|array',
            'data_sources.*' => 'string',
            'data_formats' => 'nullable|array',
            'data_formats.*' => 'string',
        ]);

        // Default status to Pending if not provided
        $data['status'] = $data['status'] ?? 'Pending';
        $data['user_id'] = auth()->id();

        $ropa = Ropa::create($data);

        // Initialize default risk weights
        foreach ($this->getScoreableFields() as $field) {
            $ropa->riskWeightSettings()->create([
                'field_name' => $field,
                'weight' => 0,
            ]);
        }

        return redirect()->route('ropa.index')->with('success', 'ROPA record created successfully.');
    }

    /**
     * Display the specified resource
     */
    public function show(Ropa $ropa)
    {
        return view('ropa.show', compact('ropa'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(Ropa $ropa)
    {
        $lawfulOptions = $this->getLawfulBasisOptions();
        $personalDataOptions = $this->getPersonalDataCategories();

        $selectedLawfulBasis = $ropa->lawful_basis ?? [];
        $selectedPersonalData = $ropa->personal_data_category ?? [];
        $selectedProcesses = $ropa->processes ?? [];
        $selectedSources = $ropa->data_sources ?? [];
        $selectedFormats = $ropa->data_formats ?? [];

        return view('ropa.edit', compact(
            'ropa',
            'lawfulOptions',
            'personalDataOptions',
            'selectedLawfulBasis',
            'selectedPersonalData',
            'selectedProcesses',
            'selectedSources',
            'selectedFormats'
        ));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, Ropa $ropa)
    {
        $data = $request->validate([
            'status' => 'nullable|string|in:Pending,Reviewed',
            'date_submitted' => 'nullable|date',
            'other_specify' => 'nullable|string|max:255',
            'information_shared' => 'nullable|boolean',
            'information_nature' => 'nullable|string',
            'outsourced_processing' => 'nullable|boolean',
            'processor' => 'nullable|string|max:255',
            'transborder_processing' => 'nullable|boolean',
            'country' => 'nullable|string|max:255',
            'lawful_basis' => 'nullable|array',
            'lawful_basis.*' => 'string',
            'retention_period_years' => 'nullable|integer',
            'retention_rationale' => 'nullable|string',
            'users_count' => 'nullable|integer',
            'access_control' => 'nullable|boolean',
            'personal_data_category' => 'nullable|array',
            'personal_data_category.*' => 'string',
            'organisation_name' => 'nullable|string|max:255',
            'department_name' => 'nullable|string|max:255',
            'other_department' => 'nullable|string|max:255',
            'processes' => 'nullable|array',
            'processes.*' => 'string',
            'data_sources' => 'nullable|array',
            'data_sources.*' => 'string',
            'data_formats' => 'nullable|array',
            'data_formats.*' => 'string',
        ]);

        $ropa->update($data);

        // Update dynamic risk weights if submitted
        if ($request->has('weights')) {
            foreach ($request->input('weights') as $field => $weight) {
                if (in_array($field, $this->getScoreableFields())) {
                    $ropa->riskWeightSettings()->updateOrCreate(
                        ['field_name' => $field],
                        ['weight' => $weight]
                    );
                }
            }
        }

        return redirect()->route('ropa.index')->with('success', 'ROPA record updated successfully.');
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(Ropa $ropa)
    {
        $ropa->delete();

        return redirect()->route('ropa.index')->with('success', 'ROPA record deleted successfully.');
    }

    /**
     * List of fields that contribute to risk score
     */
    private function getScoreableFields(): array
    {
        return [
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
            'processes',
            'data_sources',
            'data_formats',
        ];
    }

    /**
     * Lawful basis options
     */
    private function getLawfulBasisOptions(): array
    {
        return [
            'Consent',
            'Contractual Obligation',
            'Legal Obligation',
            'Vital Interest',
            'Public Interest',
            'Legitimate Interest',
            'Where The Data Subject Has Made The Information Public',
            'Scientific Research',
        ];
    }

    /**
     * Personal data category options
     */
    private function getPersonalDataCategories(): array
    {
        return [
            'Name',
            'Email',
            'Phone Number',
            'Address',
            'Date of Birth',
            'Identification Number',
            'Health Data',
            'Financial Data',
            'Employment Data',
            'Other',
        ];
    }

    
    public function adminIndex()
{
    $ropas = Ropa::with('user')->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.dashboard', compact('ropas'));
}


public function updateStatus(Request $request, Ropa $ropa)
{
    $request->validate(['status' => 'required|in:Pending,Reviewed']);
    $ropa->update(['status' => $request->status]);
    return response()->json(['message' => 'Status updated successfully']);
}


public function print(Ropa $ropa)
{
    // Example: generate a PDF (using a package like barryvdh/laravel-dompdf)
    $pdf = \PDF::loadView('ropa.pdf', compact('ropa'));

    return $pdf->download('ROPA_' . $ropa->id . '.pdf');
}


}
