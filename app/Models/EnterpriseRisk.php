<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnterpriseRisk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'enterprise_risks';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'risk_id',
        'title',
        'description',
        'department',
        'risk_category',
        'risk_level',
        'likelihood',
        'impact',
        'inherent_risk_score',
        'current_controls',
        'residual_risk_score',
        'mitigation_plan',
        'action',
        'expected_response',
        'owner_id',
        'response_owner',
        'status',
        'target_date',
        'review_date',
        'source_type',
        'source_id',
    ];


    protected static function booted()
{
    static::creating(function ($risk) {
        if (empty($risk->risk_id)) {
            $year = now()->year;

            $lastRisk = self::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastRisk
                ? ((int) substr($lastRisk->risk_id, -4)) + 1
                : 1;

            $risk->risk_id = sprintf(
                'Risk-%d-%04d',
                $year,
                $nextNumber
            );
        }

        // Ensure inherent risk score is correct
        if (empty($risk->inherent_risk_score)) {
            $risk->inherent_risk_score = $risk->likelihood * $risk->impact;
        }

        // Ensure risk_level matches score
        $risk->risk_level = $risk->determineRiskLevel();
    });
}


    /**
     * Casts
     */
    protected $casts = [
        'likelihood'           => 'integer',
        'impact'               => 'integer',
        'inherent_risk_score'  => 'integer',
        'residual_risk_score'  => 'integer',
        'target_date'          => 'date',
        'review_date'          => 'date',
    ];

    // ---------------------------------------------------
    // Relationships
    // ---------------------------------------------------
    
    /**
     * Get the user who owns this risk
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the associated ROPA record if source_type is 'ROPA'
     */
    public function ropa()
    {
        return $this->belongsTo(Ropa::class, 'source_id')->where('source_type', 'ROPA');
    }

    /**
     * Polymorphic relationship to handle multiple source types
     */
    public function source()
    {
        if ($this->source_type === 'ROPA') {
            return $this->ropa();
        }
        // Add other source types as needed
        return null;
    }

    // ---------------------------------------------------
    // Query Scopes
    // ---------------------------------------------------
    
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeMitigated($query)
    {
        return $query->where('status', 'mitigated');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeHighPriority($query)
    {
        return $query->whereIn('risk_level', ['high', 'critical']);
    }

    public function scopeCritical($query)
    {
        return $query->where('risk_level', 'critical');
    }

    public function scopeFromRopa($query)
    {
        return $query->where('source_type', 'ROPA');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    // ---------------------------------------------------
    // Helper Methods
    // ---------------------------------------------------
    
    /**
     * Calculate and update inherent risk score
     */
    public function calculateInherentRiskScore(): int
    {
        return $this->impact * $this->likelihood;
    }

    /**
     * Update the inherent risk score
     */
    public function updateInherentRiskScore(): void
    {
        $this->inherent_risk_score = $this->calculateInherentRiskScore();
        $this->save();
    }

    /**
     * Determine risk level based on inherent risk score
     */
    public function determineRiskLevel(): string
    {
        $score = $this->inherent_risk_score ?? $this->calculateInherentRiskScore();

        return match (true) {
            $score >= 20 => 'critical',  // 20-25
            $score >= 12 => 'high',      // 12-19
            $score >= 6 => 'medium',     // 6-11
            default => 'low',            // 1-5
        };
    }

    /**
     * Check if risk is from ROPA
     */
    public function isFromRopa(): bool
    {
        return $this->source_type === 'ROPA' && $this->source_id !== null;
    }

    /**
     * Get risk level badge color
     */
    public function getRiskLevelColorAttribute(): string
    {
        return match ($this->risk_level) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray',
        };
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => 'blue',
            'in_progress' => 'purple',
            'mitigated' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }
}