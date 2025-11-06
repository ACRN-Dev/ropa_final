<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Ropa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        // Display all ROPA records with their reviews
        $ropas = Ropa::with('reviews')->paginate(10);
        $reviews = Review::with('ropa')->latest()->paginate(10);
        return view('admindashboard.review.index', compact('ropas','reviews'));
    }

    public function create(Ropa $ropa)
    {
        // Show review form for a specific ROPA
        return view('reviews.create', compact('ropa'));
    }

    public function store(Request $request, Ropa $ropa)
    {
        $data = $request->validate([
            'review_status' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'risk_score' => 'nullable|integer|min:1|max:5',
        ]);

        $data['ropa_id'] = $ropa->id;
        $data['reviewed_by'] = Auth::user()->name ?? 'Admin';

        Review::create($data);

        return redirect()->route('reviews.index')->with('success', 'Review submitted successfully.');
    }

    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
