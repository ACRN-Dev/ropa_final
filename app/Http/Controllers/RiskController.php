<?php

namespace App\Http\Controllers;

use App\Models\EnterpriseRisk;
use App\Imports\RiskRegisterImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'risk_level' => 'required|in:low,medium,high,critical',
            'current_controls' => 'nullable|string',
            'residual_risk_score' => 'nullable|integer',
            'mitigation_plan' => 'nullable|string',
            'action' => 'nullable|string',
            'expected_response' => 'nullable|string',
            'status' => 'required|in:open,in_progress,mitigated,closed',
            'response_owner' => 'nullable|string',
            'target_date' => 'nullable|date',
            'review_date' => 'nullable|date',
        ]);

        // Set owner
        $validated['owner_id'] = Auth::id();

        // Calculate inherent risk score
        $validated['inherent_risk_score'] = $validated['likelihood'] * $validated['impact'];

        // Create risk (risk_id is auto-generated)
        EnterpriseRisk::create($validated);

        return redirect()
            ->route('risk-register.index')
            ->with('success', 'Risk has been successfully added to the register.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $risk = EnterpriseRisk::findOrFail($id);
        return view('risk.show', compact('risk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnterpriseRisk $risk_register)
    {
        $risk = $risk_register;
        return view('risk.edit', compact('risk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EnterpriseRisk $risk_register)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'risk_category' => 'required|string',
            'risk_level' => 'required|in:low,medium,high,critical',
            'likelihood' => 'required|integer|min:1|max:5',
            'impact' => 'required|integer|min:1|max:5',
            'current_controls' => 'nullable|string',
            'residual_risk_score' => 'nullable|integer',
            'mitigation_plan' => 'nullable|string',
            'action' => 'nullable|string',
            'expected_response' => 'nullable|string',
            'status' => 'required|in:open,in_progress,mitigated,closed',
            'response_owner' => 'nullable|string',
            'target_date' => 'nullable|date',
            'review_date' => 'nullable|date',
        ]);

        // Recalculate inherent risk score if likelihood or impact changed
        $validated['inherent_risk_score'] = $validated['likelihood'] * $validated['impact'];

        $risk_register->update($validated);

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

    /**
     * Import risks from Excel file
     */
   
    public function import(Request $request)
    {
        // Log the request
        \Log::info('Import started', [
            'has_file' => $request->hasFile('file'),
            'file_name' => $request->file('file')?->getClientOriginalName(),
        ]);

        // Validate file
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120'
        ], [
            'file.required' => 'Please select a file to upload',
            'file.file' => 'The uploaded file is invalid',
            'file.mimes' => 'File must be CSV, XLS, or XLSX format',
            'file.max' => 'File size must not exceed 5MB',
        ]);

        try {
            $file = $request->file('file');
            
            // Log file details
            \Log::info('File validation passed', [
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            // Handle CSV files directly
            if ($file->getClientOriginalExtension() === 'csv') {
                $this->importFromCsv($file);
            } else {
                // Handle Excel files
                Excel::import(new RiskRegisterImport(), $file);
            }
            
            \Log::info('Import completed successfully');
            
            return redirect()->route('risk-register.index')
                ->with('success', 'Risks imported successfully!');
                
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMsg = 'Import validation failed: ';
            
            \Log::error('Excel validation failed', [
                'failures_count' => count($failures),
                'errors' => $failures,
            ]);
            
            foreach ($failures as $failure) {
                $errorMsg .= "Row {$failure->row()}: " . implode(', ', $failure->errors()) . ". ";
            }
            
            return redirect()->route('risk-register.index')
                ->with('error', $errorMsg);
                
        } catch (\Exception $e) {
            \Log::error('Import failed with exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('risk-register.index')
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    /**
     * Import risks from CSV file
     */
    private function importFromCsv($file)
    {
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle); // Skip header row
        
        $imported = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            try {
                $data = array_combine($headers, $row);

                // Skip if no title or description
                if (empty($data['title']) || empty($data['description'])) {
                    continue;
                }

                $riskData = [
                    'risk_id' => $data['risk_id'] ?? 'RISK-' . strtoupper(uniqid()),
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'department' => $data['department'] ?? auth()->user()->department ?? 'Not Assigned',
                    'risk_category' => $data['risk_category'] ?? null,
                    'likelihood' => (int)($data['likelihood'] ?? 1),
                    'impact' => (int)($data['impact'] ?? 1),
                    'status' => $data['status'] ?? 'open',
                    'response_owner' => $data['response_owner'] ?? null,
                    'current_controls' => $data['current_controls'] ?? null,
                    'residual_risk_score' => !empty($data['residual_risk_score']) ? (int)$data['residual_risk_score'] : null,
                    'mitigation_plan' => $data['mitigation_plan'] ?? null,
                    'action' => $data['action'] ?? null,
                    'expected_response' => $data['expected_response'] ?? null,
                    'owner_id' => auth()->id(),
                ];

                // Parse dates
                if (!empty($data['target_date'])) {
                    $riskData['target_date'] = \Carbon\Carbon::parse($data['target_date']);
                }
                if (!empty($data['review_date'])) {
                    $riskData['review_date'] = \Carbon\Carbon::parse($data['review_date']);
                }

                // Calculate risk level
                $score = $riskData['likelihood'] * $riskData['impact'];
                if ($score >= 20) {
                    $riskData['risk_level'] = 'critical';
                } elseif ($score >= 12) {
                    $riskData['risk_level'] = 'high';
                } elseif ($score >= 6) {
                    $riskData['risk_level'] = 'medium';
                } else {
                    $riskData['risk_level'] = 'low';
                }

                // Calculate inherent risk score
                $riskData['inherent_risk_score'] = $score;

                EnterpriseRisk::create($riskData);
                $imported++;

            } catch (\Exception $e) {
                \Log::error('Error importing row', [
                    'row_data' => $row,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $e->getMessage();
            }
        }

        fclose($handle);

        \Log::info('CSV import completed', [
            'imported' => $imported,
            'errors_count' => count($errors),
        ]);

        if (!empty($errors)) {
            \Log::warning('CSV import had errors', ['errors' => $errors]);
        }
    }


    /**
     * Export selected risks as CSV
     */

    public function exportCsv(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Please select risks to export');
        }

        $risks = EnterpriseRisk::whereIn('id', $ids)->get();

        if ($risks->isEmpty()) {
            return back()->with('error', 'No risks found to export');
        }

        $filename = 'risks_' . date('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function () use ($risks) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Risk ID',
                'Title',
                'Department',
                'Category',
                'Description',
                'Likelihood',
                'Impact',
                'Risk Level',
                'Status',
                'Owner',
                'Current Controls',
                'Residual Risk Score',
                'Mitigation Plan',
                'Target Date',
                'Review Date',
                'Created At',
                'Updated At'
            ]);

            // Data rows
            foreach ($risks as $risk) {
                fputcsv($file, [
                    $risk->risk_id,
                    $risk->title,
                    $risk->department,
                    $risk->risk_category,
                    $risk->description,
                    $risk->likelihood,
                    $risk->impact,
                    ucfirst($risk->risk_level),
                    ucfirst(str_replace('_', ' ', $risk->status)),
                    $risk->response_owner ?? 'N/A',
                    $risk->current_controls,
                    $risk->residual_risk_score,
                    $risk->mitigation_plan,
                    $risk->target_date ? $risk->target_date->format('Y-m-d') : '',
                    $risk->review_date ? $risk->review_date->format('Y-m-d') : '',
                    $risk->created_at->format('Y-m-d H:i:s'),
                    $risk->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\""
        ]);
    }

    /**
     * Export selected risks as PDF
     */
    public function exportPdf(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Please select risks to export');
        }

        $risks = EnterpriseRisk::whereIn('id', $ids)->get();

        if ($risks->isEmpty()) {
            return back()->with('error', 'No risks found to export');
        }
        
        $pdf = Pdf::loadView('risk.export-pdf', ['risks' => $risks])
            ->setPaper('a4', 'landscape');
        
        $filename = 'risks_' . date('Y-m-d_H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Bulk delete risks
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return back()->with('error', 'Please select risks to delete');
        }

        try {
            $count = EnterpriseRisk::whereIn('id', $ids)->delete();
            
            return redirect()->route('risk-register.index')
                ->with('success', "$count risk(s) deleted successfully!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting risks: ' . $e->getMessage());
        }
    }

    /**
     * Download Excel template for importing risks
     */
 
    public function downloadTemplate()
    {
        $fileName = 'Risk_Register_Template_' . date('Y-m-d') . '.csv';

        $headers = [
            'risk_id',
            'title',
            'description',
            'department',
            'risk_category',
            'likelihood',
            'impact',
            'status',
            'response_owner',
            'current_controls',
            'residual_risk_score',
            'mitigation_plan',
            'action',
            'expected_response',
            'target_date',
            'review_date'
        ];

        $exampleData = [
            ['RISK-001', 'Data Breach Risk', 'Potential unauthorized access to customer data due to inadequate security measures', 'Information Technology', 'Security', '4', '5', 'open', 'John Doe', 'Firewall, Multi-factor authentication, Data encryption', '15', 'Implement advanced threat detection system', '1. Deploy IDS/IPS, 2. Regular testing', 'Reduce risk score from 20 to 8', '2024-06-30', '2024-03-31'],
            ['RISK-002', 'Compliance Risk', 'Non-compliance with GDPR and local data protection regulations', 'Legal & Compliance', 'Compliance', '3', '4', 'in_progress', 'Jane Smith', 'Privacy policy review, Data protection officer', '10', 'Conduct compliance audit', '1. GDPR gap analysis, 2. Update policies', 'Achieve 100% compliance', '2024-05-15', '2024-04-15'],
            ['RISK-003', 'Operational Risk', 'System downtime due to inadequate backup and disaster recovery', 'Operations', 'Operational', '2', '3', 'open', 'Mike Johnson', 'Regular backups, Secondary data centers', '4', 'Implement automated backup system', '1. Deploy automation, 2. RTO/RPO targets', 'Achieve 99.9% availability', '2024-07-30', '2024-04-30'],
            ['RISK-004', 'Market Risk', 'Adverse market conditions affecting business performance', 'Finance', 'Financial', '3', '4', 'mitigated', 'Sarah Williams', 'Market monitoring, Diversified portfolio', '8', 'Diversify revenue streams', '1. Expand offerings, 2. New markets', 'Maintain 15% margin', '2024-08-30', '2024-06-30'],
            ['RISK-005', 'Resource Risk', 'Key employee turnover and talent shortage', 'Human Resources', 'Operational', '2', '2', 'open', 'Robert Brown', 'Competitive compensation, Training programs', '3', 'Implement retention program', '1. Review compensation, 2. Mentorship', 'Reduce turnover to <10%', '2024-09-30', '2024-07-30'],
            // Blank rows for user input
            ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
            ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''],
        ];

        return response()->streamDownload(function () use ($headers, $exampleData) {
            $file = fopen('php://output', 'w');
            
            // Set UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");
            
            // Write headers
            fputcsv($file, $headers);
            
            // Write example data
            foreach ($exampleData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ]);
    }
}


