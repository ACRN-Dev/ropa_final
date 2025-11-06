<?php

namespace App\Http\Controllers;

use App\Models\Ropa;
use App\Models\Review;
use Illuminate\Http\Request;

class RopaController extends Controller
{


    // Add this constructor here
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $reviews = Review::with('ropa')->paginate(10);
        $ropas = Ropa::latest()->paginate(10);

        return view('ropa.index', compact('reviews', 'ropas'));
    }

//     public function adminIndex()
// {
//     // Fetch all submitted ROPAs with their users
//     $ropas = Ropa::with('user')
//         ->orderBy('date_submitted', 'desc')
//         ->paginate(10);

//     return view('admindashboard.management.index', compact('ropas'));
// }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ropas = Ropa::paginate(10);
        
        return view('ropa.create', compact('ropas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'status' => 'nullable|string|max:255',
            'date_submitted' => 'nullable|date',
            'other_specify' => 'nullable|string|max:255',
            'information_shared' => 'nullable|string|max:255',
            'information_nature' => 'nullable|string',
            'outsourced_processing' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'transborder_processing' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'lawful_basis' => 'nullable|string',
            'retention_period_years' => 'nullable|integer',
            'retention_rationale' => 'nullable|string',
            'users_count' => 'nullable|integer',
            'access_control' => 'nullable|string',
            'personal_data_category' => 'nullable|string|max:255',
            'organisation_name' => 'nullable|string|max:255',
            'department_name' => 'nullable|string|max:255',
            'other_department' => 'nullable|string|max:255',
        ]);

        // Add the current user's ID before saving
        $data['user_id'] = auth()->id();

        Ropa::create($data);

        return redirect()->route('ropas.index')->with('success', 'ROPA record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ropa $ropa)
    {
        return view('ropa.show', compact('ropa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ropa $ropa)
    {
        return view('ropa.edit', compact('ropa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ropa $ropa)
    {
        $data = $request->validate([
            'status' => 'nullable|string|max:255',
            'date_submitted' => 'nullable|date',
            'other_specify' => 'nullable|string|max:255',
            'information_shared' => 'nullable|string|max:255',
            'information_nature' => 'nullable|string',
            'outsourced_processing' => 'nullable|string|max:255',
            'processor' => 'nullable|string|max:255',
            'transborder_processing' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'lawful_basis' => 'nullable|string',
            'retention_period_years' => 'nullable|integer',
            'retention_rationale' => 'nullable|string',
            'users_count' => 'nullable|integer',
            'access_control' => 'nullable|string',
            'personal_data_category' => 'nullable|string|max:255',
            'organisation_name' => 'nullable|string|max:255',
            'department_name' => 'nullable|string|max:255',
            'other_department' => 'nullable|string|max:255',
        ]);

        $ropa->update($data);

        return redirect()->route('ropas.index')->with('success', 'ROPA record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ropa $ropa)
    {
        $ropa->delete();

        return redirect()->route('ropas.index')->with('success', 'ROPA record deleted successfully.');
    }






    public function adminIndex(Request $request)
{
    $search = $request->input('search');

    $ropas = Ropa::with('user')
        ->when($search, function ($query, $search) {
            $query->where('organisation_name', 'like', "%{$search}%")
                ->orWhere('department_name', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        })
        ->orderBy('date_submitted', 'desc')
        ->paginate(10);

    return view('admindashboard.management.index', compact('ropas'));
}

}
