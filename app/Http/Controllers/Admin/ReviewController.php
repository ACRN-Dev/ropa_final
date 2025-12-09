<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Ropa;
use App\Models\Comment;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReviewsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * ADMIN: List all reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'ropa']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($qr) use ($search) {
                $qr->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                   ->orWhereHas('ropa', fn($r) => $r->where('id', $search));
            });
        }

        if ($request->filled('dpa')) {
            $query->where('data_processing_agreement', $request->dpa);
        }

        if ($request->filled('dpia')) {
            $query->where('data_protection_impact_assessment', $request->dpia);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'score_high':
                    $query->orderBy('average_score', 'desc');
                    break;
                case 'score_low':
                    $query->orderBy('average_score', 'asc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $reviews = $query->paginate(10)->withQueryString();

        return view('admindashboard.review.index', compact('reviews'));
    }

    /**
     * ADMIN: Show a specific review
     */
    public function show($id)
    {
        $review = Review::with([
            'user',
            'ropa',
            'comments.user'
        ])->findOrFail($id);

        $review->section_scores = is_array($review->section_scores) ? $review->section_scores : [];

        if (!$review->user_id && auth()->check()) {
            $review->user_id = auth()->id();
            $review->save();
        }

        return view('admindashboard.review.show', compact('review'));
    }

    /**
     * ADMIN: Update section scores and risk-related fields
     */
    public function update(Request $request, $id)
{
    $review = Review::findOrFail($id);

    // Validation
    $validated = $request->validate([
        'section_scores' => 'nullable|array',
        'section_scores.*' => 'nullable|numeric|min:0|max:10',
        'risks' => 'nullable|array',
        'risks.*.name' => 'required|string|max:255',
        'risks.*.probability' => 'required|numeric|min:1|max:5',
        'risks.*.impact' => 'required|numeric|min:1|max:5',
        'mitigation_measures' => 'nullable|string',
        'children_data_transfer' => 'nullable|boolean',
        'vulnerable_population_transfer' => 'nullable|boolean',
        'data_processing_agreement_file' => 'nullable|file|mimes:pdf,doc,docx',
        'data_protection_impact_assessment_file' => 'nullable|file|mimes:pdf,doc,docx',
        'data_sharing_agreement' => 'nullable|file|mimes:pdf,doc,docx',
    ]);

    // Update section scores without affecting other ROPA data
    if(isset($validated['section_scores'])){
        $scores = collect($validated['section_scores'])
            ->map(fn($score) => is_numeric($score) ? (int)$score : 0)
            ->toArray();
        $review->section_scores = $scores;
    }

    // Update risks and mitigation measures
    $review->risks = $validated['risks'] ?? $review->risks;
    $review->mitigation_measures = $validated['mitigation_measures'] ?? $review->mitigation_measures;

    // Update data transfer flags properly
    $review->children_data_transfer = $validated['children_data_transfer'] ?? 0;
    $review->vulnerable_population_transfer = $validated['vulnerable_population_transfer'] ?? 0;

    // Handle file uploads without overwriting existing files if not uploaded
    foreach (['data_processing_agreement_file', 'data_protection_impact_assessment_file', 'data_sharing_agreement'] as $fileField) {
        if ($request->hasFile($fileField)) {
            $path = $request->file($fileField)->store('reviews', 'public');
            $review->$fileField = $path;
        }
    }

    // Recalculate risk scores
    $review->overall_risk_score = $review->calculated_overall_risk_score;
    $review->impact_level = $review->calculated_impact_level;

    $review->save();

    return redirect()
        ->route('admin.reviews.show', $review->id)
        ->with('success', 'Review updated successfully.');
}


    /**
     * ADMIN: Update compliance checkboxes (DPA / DPIA)
     */
    public function updateCompliance(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $review->update([
            'data_processing_agreement' => $request->has('data_processing_agreement'),
            'data_protection_impact_assessment' => $request->has('data_protection_impact_assessment'),
        ]);

        return redirect()
            ->route('admin.reviews.show', $review->id)
            ->with('success', 'Compliance fields updated.');
    }

    /**
     * ADMIN: Delete a review
     */
    public function destroy($id)
    {
        Review::findOrFail($id)->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Export reviews to Excel
     */
    public function export()
    {
        return Excel::download(new ReviewsExport, 'reviews.xlsx');
    }

    /**
     * Dashboard: risk summary
     */
    public function reviewRiskDashboard()
    {
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
            'criticalRisk', 'highRisk', 'mediumRisk', 'lowRisk', 'reviews'
        ));
    }

    /**
     * Add comment to a review
     */
    public function addComment(Request $request, Review $review)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $review->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully.');
    }
}
