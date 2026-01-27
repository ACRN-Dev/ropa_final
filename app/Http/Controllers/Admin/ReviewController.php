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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    /**
 * ADMIN: List all submitted ROPAs
 */
public function index(Request $request)
{
    // Get all ROPAs (both Pending and Reviewed are "submitted" ROPAs)
    $query = Ropa::with(['user', 'reviews', 'enterpriseRisks']);

    // SEARCH
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($qr) use ($search) {
            $qr->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
               ->orWhere('id', $search)
               ->orWhere('organisation_name', 'like', "%{$search}%")
               ->orWhere('department', 'like', "%{$search}%");
        });
    }

    // STATUS FILTER
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // RISK LEVEL FILTER
    if ($request->filled('risk_level')) {
        $riskLevel = $request->risk_level;
        $query->whereHas('enterpriseRisks', function($q) use ($riskLevel) {
            $q->where('risk_level', $riskLevel);
        });
    }

    // SORTING
    if ($request->filled('sort')) {
        switch ($request->sort) {
            case 'score_high':
                $query->withAvg('reviews', 'average_score')
                      ->orderBy('reviews_avg_average_score', 'desc');
                break;
            case 'score_low':
                $query->withAvg('reviews', 'average_score')
                      ->orderBy('reviews_avg_average_score', 'asc');
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

    // PAGINATION
    $ropas = $query->paginate(10)->withQueryString();

    return view('admindashboard.review.index', compact('ropas'));
}


    /**
     * ADMIN: Show single review — mark as IN PROGRESS if opened by admin
     */
    public function show($id)
    {
        $review = Review::with([
            'user',
            'ropa',
            'comments.user'
        ])->findOrFail($id);

        $review->section_scores = is_array($review->section_scores)
            ? $review->section_scores
            : [];

        // AUTO-ASSIGN reviewer & update status if needed
        if (auth()->check()) {
            if (!$review->user_id) {
                $review->user_id = auth()->id();
            }

            if ($review->status === 'Pending') {
                $review->status = 'In Progress';
            }

            $review->save();
        }

        return view('admindashboard.review.show', compact('review'));
    }

    /**
     * ADMIN: Update Review — mark as REVIEWED
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $review = Review::with('ropa')->findOrFail($id);

            // VALIDATION
            $validated = $request->validate([
                'risks.*.name' => 'required|string|max:255',
                'risks.*.probability' => 'required|numeric|min:1|max:5',
                'risks.*.impact' => 'required|numeric|min:1|max:5',

                'mitigation_measures' => 'nullable|string',

                'children_data_transfer' => 'nullable|boolean',
                'vulnerable_population_transfer' => 'nullable|boolean',

                'data_processing_agreement' => 'nullable|file|mimes:pdf,doc,docx',
                'data_protection_impact_assessment' => 'nullable|file|mimes:pdf,doc,docx',
                'data_sharing_agreement' => 'nullable|file|mimes:pdf,doc,docx',

                'ropa_id' => 'nullable|exists:ropas,id',
            ]);

            // UPDATE ROPA LINK
            if ($request->filled('ropa_id')) {
                $review->ropa_id = $validated['ropa_id'];
            }

            // RISKS → JSON
            if (isset($validated['risks'])) {
                $review->risks = json_encode($validated['risks']);
            }

            // TEXT FIELDS
            $review->mitigation_measures =
                $validated['mitigation_measures'] ?? $review->mitigation_measures;

            // CHECKBOXES
            $review->children_data_transfer =
                $validated['children_data_transfer'] ?? 0;

            $review->vulnerable_population_transfer =
                $validated['vulnerable_population_transfer'] ?? 0;

            // FILE UPLOADS
            foreach ([
                'data_processing_agreement',
                'data_protection_impact_assessment',
                'data_sharing_agreement'
            ] as $field) {
                if ($request->hasFile($field)) {
                    $review->$field = $request->file($field)->store('reviews', 'public');
                }
            }

            // MARK REVIEW AS COMPLETED
            $review->status = 'Reviewed';

            $review->save();

            DB::commit();

            return redirect()
                ->route('admin.reviews.show', $review->id)
                ->with('success', 'Review updated and marked as Reviewed.');

        } catch (\Illuminate\Validation\ValidationException $ve) {
            DB::rollBack();
            return back()->withErrors($ve->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return back()->with('error', 'Unexpected error. Please check logs.');
        }
    }

    /**
     * ADMIN: Update compliance checkboxes
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
     * Export to Excel
     */
    public function export()
    {
        return Excel::download(new ReviewsExport, 'reviews.xlsx');
    }

    /**
     * Risk Dashboard (Old Logic)
     */
    public function reviewRiskDashboard()
    {
        $reviews = Review::all();
        $total = max($reviews->count(), 1);

        $critical = $reviews->filter(fn($r) => $r->total_score <= 50)->count();
        $high     = $reviews->filter(fn($r) => $r->total_score > 50 && $r->total_score <= 100)->count();
        $medium   = $reviews->filter(fn($r) => $r->total_score > 100 && $r->total_score <= 160)->count();
        $low      = $reviews->filter(fn($r) => $r->total_score > 160)->count();

        return view('admindashboard.dashboard', [
            'criticalRisk' => round(($critical / $total) * 100, 1),
            'highRisk'     => round(($high / $total) * 100, 1),
            'mediumRisk'   => round(($medium / $total) * 100, 1),
            'lowRisk'      => round(($low / $total) * 100, 1),
            'reviews'      => $reviews,
        ]);
    }

    /**
     * Add comment
     */
    public function addComment(Request $request, Review $review)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        $review->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment added successfully.');
    }

    /**
     * Delete comment
     */
    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted successfully.');
    }
}
