<?php
// app/Imports/RiskRegisterImport.php

namespace App\Imports;

use App\Models\RiskRegister;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;

class RiskRegisterImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['title']) || empty($row['description'])) {
            return null;
        }

        // Generate risk_id if not provided
        $riskId = $row['risk_id'] ?? 'RISK-' . strtoupper(uniqid());

        // Create the risk record
        return new RiskRegister([
            'risk_id' => $riskId,
            'title' => $row['title'] ?? null,
            'description' => $row['description'] ?? null,
            'department' => $row['department'] ?? auth()->user()->department ?? 'Not Assigned',
            'risk_category' => $row['risk_category'] ?? $row['category'] ?? null,
            'likelihood' => $row['likelihood'] ?? null,
            'impact' => $row['impact'] ?? null,
            'risk_level' => $this->calculateRiskLevel(
                $row['likelihood'] ?? null,
                $row['impact'] ?? null
            ),
            'status' => $row['status'] ?? 'open',
            'response_owner' => $row['response_owner'] ?? $row['owner'] ?? null,
            'current_controls' => $row['current_controls'] ?? null,
            'residual_risk_score' => $row['residual_risk_score'] ?? null,
            'mitigation_plan' => $row['mitigation_plan'] ?? null,
            'action' => $row['action'] ?? null,
            'expected_response' => $row['expected_response'] ?? null,
            'target_date' => $this->parseDate($row['target_date'] ?? null),
            'review_date' => $this->parseDate($row['review_date'] ?? null),
        ]);
    }

    /**
     * Calculate risk level based on likelihood and impact
     */
    private function calculateRiskLevel($likelihood, $impact)
    {
        if (!$likelihood || !$impact) {
            return 'low';
        }

        $score = (int)$likelihood * (int)$impact;

        if ($score >= 20) {
            return 'critical';
        } elseif ($score >= 12) {
            return 'high';
        } elseif ($score >= 6) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Parse date in various formats
     */
    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            // If it's a numeric value (Excel date format)
            if (is_numeric($date)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date));
            }
            
            // Try to parse as string
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'likelihood' => 'nullable|numeric|min:1|max:5',
            'impact' => 'nullable|numeric|min:1|max:5',
        ];
    }
}