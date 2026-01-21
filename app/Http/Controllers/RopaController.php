<?php

namespace App\Http\Controllers;
use App\Models\Ropa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Review;
use App\Mail\ShareRopaMail;
use Illuminate\Support\Facades\Mail;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Notifications\NewRopaSubmitted;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RopaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all ROPAs or filter by user if not admin
        $query = Ropa::with('user');

        // Optional: Filter by user if they're not an admin
        // Uncomment if you have role-based access control
        // if (!auth()->user()->hasRole('admin')) {
        //     $query->where('user_id', auth()->id());
        // }
       
        $ropas = $query->latest()->paginate(15);

        return view('ropa.index', compact('ropas'));
    }

    public function create(Request $request){
         $query = Ropa::with('user');
        $ropas = $query->latest()->paginate(15);
        return view('ropa.create', compact('ropas'));
    }

public function bulkDelete(Request $request)
{
    $request->validate([
        'ids' => 'required|string',
    ]);

    $ids = explode(',', $request->ids);
    $ids = array_filter(array_map('intval', $ids));

    if (empty($ids)) {
        return redirect()->back()->with('error', 'No records selected for deletion.');
    }

    try {
        $query = Ropa::whereIn('id', $ids);
        
        // If not admin, restrict to user's own records only
        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $deletedCount = $query->delete();

        if ($deletedCount > 0) {
            return redirect()->route('ropa.index')
                ->with('success', "Successfully deleted {$deletedCount} ROPA record(s).");
        } else {
            $message = auth()->user()->isAdmin() 
                ? 'No records were deleted.' 
                : 'No records were deleted. You can only delete your own records.';
            
            return redirect()->back()->with('error', $message);
        }
    } catch (\Exception $e) {
        \Log::error('Bulk delete error: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'An error occurred while deleting records.');
    }
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('ROPA store request received', [
                'user_id' => auth()->id(),
                'request_keys' => array_keys($request->all())
            ]);

            // Normalize sharing_type if single value
            if ($request->has('sharing_type') && !is_array($request->sharing_type)) {
                $request->merge(['sharing_type' => [$request->sharing_type]]);
            }

            // -------------------------------------------------------
            // Validate incoming request
            // -------------------------------------------------------
            $validated = $request->validate([
                'organisation_name' => 'nullable|string|max:255',
                'other_organisation_name' => 'nullable|array',
                'other_organisation_name.*' => 'nullable|string|max:255',

                'department' => 'nullable|string|max:255',
                'other_department' => 'nullable|array',
                'other_department.*' => 'nullable|string|max:255',

                'processes' => 'nullable|array',
                'processes.*' => 'nullable|string|max:255',

                'data_sources' => 'nullable|array',
                'data_sources.*' => 'nullable|string|max:255',
                'data_sources_other' => 'nullable|array',
                'data_sources_other.*' => 'nullable|string|max:255',

                'data_formats' => 'nullable|array',
                'data_formats.*' => 'nullable|string|max:255',
                'data_formats_other' => 'nullable|array',
                'data_formats_other.*' => 'nullable|string|max:255',

                'personal_data_categories' => 'nullable|array',
                'personal_data_categories.*' => 'nullable|string|max:255',
                'personal_data_categories_other' => 'nullable|array',
                'personal_data_categories_other.*' => 'nullable|string|max:255',

                'data_volume' => 'nullable|array',
                'data_volume.*' => 'nullable|string|max:255',
                'retention_period_years' => 'nullable|array',
                'retention_period_years.*' => 'nullable|string|max:255',
                'retention_rationale' => 'nullable|array',
                'retention_rationale.*' => 'nullable|string|max:2000',

                'information_shared' => 'nullable|boolean',
                'sharing_type' => 'nullable|array',
                'sharing_type.*' => 'nullable|string|max:255',

                'local_organizations' => 'nullable|array',
                'local_organizations.*' => 'nullable|string|max:500',
                'transborder_countries' => 'nullable|array',
                'transborder_countries.*' => 'nullable|string|max:500',
                'sharing_comment' => 'nullable|string|max:2000',

                'access_control' => 'nullable|boolean',

                'technical_measures' => 'nullable|array',
                'technical_measures.*' => 'nullable|string|max:255',
                'technical_measures_other' => 'nullable|array',
                'technical_measures_other.*' => 'nullable|string|max:255',

                'organisational_measures' => 'nullable|array',
                'organisational_measures.*' => 'nullable|string|max:255',
                'organisational_measures_other' => 'nullable|array',
                'organisational_measures_other.*' => 'nullable|string|max:255',

                'lawful_basis' => 'nullable|array',
                'lawful_basis.*' => 'nullable|string|max:255',
                'lawful_basis_other' => 'nullable|array',
                'lawful_basis_other.*' => 'nullable|string|max:255',

                'risk_report' => 'nullable|string|max:2000',
            ]);

            $validated['user_id'] = auth()->id();
            $validated['status'] = Ropa::STATUS_PENDING;

            // -------------------------------------------------------
            // Convert arrays to JSON where necessary
            // -------------------------------------------------------
            foreach ($validated as $key => $value) {
                if (is_array($value)) {
                    $validated[$key] = json_encode($value);
                }
            }

            // -------------------------------------------------------
            // CREATE THE ROPA
            // -------------------------------------------------------
            $ropa = Ropa::create($validated);

            Log::info('ROPA created successfully', [
                'ropa_id' => $ropa->id,
                'user_id' => auth()->id()
            ]);

            // -------------------------------------------------------
            // CREATE EMPTY REVIEW RECORD
            // -------------------------------------------------------
            $review = Review::create([
                'ropa_id' => $ropa->id,
                'user_id' => null,
                'comment' => null,
                'data_processing_agreement' => false,
                'data_protection_impact_assessment' => false,
            ]);

            return redirect()
                ->route('ropa.index')
                ->with('success', 'ROPA record created successfully.');

        } catch (\Throwable $e) {
            Log::error('ROPA STORE FAILED', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Something went wrong while saving the ROPA record.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ropa $ropa)
    {
        return view('ropa.show', compact('ropa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ropa $ropa)
    {
        $validated = $this->validateRopa($request);
        $ropa->update($validated);

        return response()->json([
            'message' => 'ROPA record updated successfully',
            'data' => $ropa
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ropa $ropa)
    {
        $ropa->delete();

        return response()->json([
            'message' => 'ROPA record deleted successfully'
        ]);
    }

    /**
     * Validate ROPA request data.
     */
    private function validateRopa(Request $request)
    {
        return $request->validate([
            'organisation_name' => 'nullable|string|max:255',
            'other_organisation_name' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'other_department' => 'nullable|string|max:255',
            'processes' => 'nullable|array',
            'data_sources' => 'nullable|array',
            'data_sources_other' => 'nullable|array',
            'data_formats' => 'nullable|array',
            'data_formats_other' => 'nullable|array',
            'information_nature' => 'nullable|array',
            'personal_data_categories' => 'nullable|array',
            'personal_data_categories_other' => 'nullable|array',
            'records_count' => 'nullable|array',
            'data_volume' => 'nullable|array',
            'retention_period_years' => 'nullable|array',
            'access_estimate' => 'nullable|array',
            'retention_rationale' => 'nullable|array',
            'information_shared' => 'nullable|boolean',
            'sharing_local' => 'nullable|boolean',
            'sharing_transborder' => 'nullable|boolean',
            'local_organizations' => 'nullable|array',
            'transborder_countries' => 'nullable|array',
            'sharing_comment' => 'nullable|string',
            'access_control' => 'nullable|boolean',
            'access_measures' => 'nullable|array',
            'technical_measures' => 'nullable|array',
            'organisational_measures' => 'nullable|array',
            'lawful_basis' => 'nullable|array',
            'risk_report' => 'nullable|array',
            'status' => 'nullable|string|in:Pending,Reviewed'
        ]);
    }

    public function export($id)
    {
        $ropa = Ropa::with('user')->findOrFail($id);

        $fileName = 'ropa_' . $ropa->id . '_export.xlsx';

        if (ob_get_length()) {
            ob_clean();
        }

        return SimpleExcelWriter::streamDownload($fileName)
            ->addHeader([
                'ID',
                'User',
                'Status',
                'Organisation Name',
                'Department',
                'Processes',
                'Data Sources',
                'Data Formats',
                'Information Nature',
                'Personal Data Categories',
                'Information Shared',
                'Sharing Local',
                'Sharing Transborder',
                'Access Control',
                'Local Organizations',
                'Transborder Countries',
                'Risk Report',
                'Records Count',
                'Retention Period (Years)',
                'Data Volume',
                'Access Estimate',
                'Created At'
            ])
            ->addRow([
                (string) $ropa->id,
                (string) optional($ropa->user)->name,
                (string) $ropa->status,
                $this->toString($ropa->organisation_name),
                $this->toString($ropa->department),
                $this->safeList($ropa->processes),
                $this->safeList($ropa->data_sources),
                $this->safeList($ropa->data_formats),
                $this->safeList($ropa->information_nature),
                $this->safeList($ropa->personal_data_categories),
                $ropa->information_shared ? 'Yes' : 'No',
                $ropa->sharing_local ? 'Yes' : 'No',
                $ropa->sharing_transborder ? 'Yes' : 'No',
                $ropa->access_control ? 'Yes' : 'No',
                $this->safeList($ropa->local_organizations),
                $this->safeList($ropa->transborder_countries),
                $this->safeList($ropa->risk_report),
                $this->safeList($ropa->records_count),
                $this->safeList($ropa->retention_period_years),
                $this->safeList($ropa->data_volume),
                $this->safeList($ropa->access_estimate),
                $ropa->created_at->toDateTimeString(),
            ])
            ->close();
    }

    /**
     * Convert string or simple array to string.
     */
    private function toString($value)
    {
        return is_array($value) ? implode(', ', $value) : (string) $value;
    }

    /**
     * Flatten nested arrays safely and convert to comma-separated string.
     */
    private function safeList($value)
    {
        if (is_null($value)) {
            return '';
        }

        $flat = collect($value)->flatten()->filter()->all();

        return implode(', ', $flat);
    }

    public function print($id)
    {
        $ropa = Ropa::findOrFail($id);

        $formatArray = function ($value) {
            if (is_array($value)) return implode(', ', $value);
            return $value ?? 'N/A';
        };

        $data = [
            'organisation_name'           => $ropa->organisation_name,
            'other_organisation_name'     => $ropa->other_organisation_name,
            'department'                  => $ropa->department,
            'other_department'            => $ropa->other_department,
            'processes'                   => $formatArray($ropa->processes),
            'data_sources'                => $formatArray($ropa->data_sources),
            'data_sources_other'          => $formatArray($ropa->data_sources_other),
            'data_formats'                => $formatArray($ropa->data_formats),
            'data_formats_other'          => $formatArray($ropa->data_formats_other),
            'information_nature'          => $formatArray($ropa->information_nature),
            'personal_data_categories'    => $formatArray($ropa->personal_data_categories),
            'personal_data_categories_other' => $formatArray($ropa->personal_data_categories_other),
            'records_count'               => $formatArray($ropa->records_count),
            'data_volume'                 => $formatArray($ropa->data_volume),
            'retention_period_years'      => $formatArray($ropa->retention_period_years),
            'access_estimate'             => $formatArray($ropa->access_estimate),
            'retention_rationale'         => $formatArray($ropa->retention_rationale),
            'information_shared'          => $ropa->information_shared ? 'Yes' : 'No',
            'sharing_local'               => $ropa->sharing_local ? 'Yes' : 'No',
            'sharing_transborder'         => $ropa->sharing_transborder ? 'Yes' : 'No',
            'local_organizations'         => $formatArray($ropa->local_organizations),
            'transborder_countries'       => $formatArray($ropa->transborder_countries),
            'sharing_comment'             => $ropa->sharing_comment ?? 'N/A',
            'access_control'              => $ropa->access_control ? 'Yes' : 'No',
            'access_measures'             => $formatArray($ropa->access_measures),
            'technical_measures'          => $formatArray($ropa->technical_measures),
            'organisational_measures'     => $formatArray($ropa->organisational_measures),
            'lawful_basis'                => $formatArray($ropa->lawful_basis),
            'risk_report'                 => $formatArray($ropa->risk_report),
            'created_at'                  => $ropa->created_at->format('d M Y H:i'),
            'submitted_by'                => optional($ropa->user)->name,
        ];

        return view('ropa.print', compact('data', 'ropa'));
    }

    public function showShareModal($id)
    {
        $userId = auth()->id();

        $ropa = Ropa::where('id', $id)
                    ->where('user_id', $userId)
                    ->firstOrFail();

        return view('modals.share-ropa', compact('ropa'));
    }

    public function sendEmail(Request $request, $id)
    {
        $request->validate([
            'email'   => 'required|email',
            'cc'      => 'nullable|string',
            'subject' => 'required|string|max:255',
            'format'  => 'required|in:pdf,excel',
        ]);

        $ropa = Ropa::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $ccList = [];
        if (!empty($request->cc)) {
            foreach (explode(',', $request->cc) as $email) {
                $email = trim($email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $ccList[] = $email;
                }
            }
        }

        $ext = $request->format === 'pdf' ? 'pdf' : 'xlsx';
        $fileName = "ropa_{$ropa->id}.$ext";
        $filePath = storage_path("app/$fileName");

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('emails.ropa-pdf', compact('ropa'));
            $pdf->save($filePath);
        } else {
            Excel::store(new RopaExport($ropa), $fileName);
        }

        Mail::to($request->email)
            ->cc($ccList)
            ->send(new ShareRopaMail($ropa, $request->subject, $filePath, $ccList));

        return back()->with('success', 'ROPA shared successfully!');
    }

    public function review($id)
    {
        $record = Ropa::findOrFail($id);
        return view('ropa.review', compact('record'));
    }

    public function archive(Ropa $ropa)
    {
        $ropa->update(['archived' => true]);
        return back()->with('success', 'ROPA archived successfully');
    }

    public function restore(Ropa $ropa)
    {
        $ropa->update(['archived' => false]);
        return back()->with('success', 'ROPA restored successfully');
    }
}