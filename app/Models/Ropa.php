<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RiskWeightSetting;
use App\Models\Review;
use App\Models\RiskScore;
use App\Models\User;

class Ropa extends Model
{
    use HasFactory;

    // Define possible status values as constants
    const STATUS_PENDING = 'Pending';
    const STATUS_REVIEWED = 'Reviewed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'date_submitted',
        'other_specify',
        'information_shared',
        'information_nature',
        'outsourced_processing',
        'processor',
        'transborder_processing',
        'country',
        'lawful_basis',            // multiple selection
        'retention_period_years',
        'retention_rationale',
        'users_count',
        'access_control',
        'personal_data_category',  // multiple selection
        'organisation_name',
        'department_name',
        'other_department',
        'processes',               // multiple processes
        'data_sources',            // multiple data sources
        'data_formats',            // multiple data formats
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_submitted' => 'datetime',
        'retention_period_years' => 'integer',
        'users_count' => 'integer',
        'information_shared' => 'boolean',
        'outsourced_processing' => 'boolean',
        'transborder_processing' => 'boolean',
        'access_control' => 'boolean',
        'lawful_basis' => 'array',
        'personal_data_category' => 'array',
        'processes' => 'array',
        'data_sources' => 'array',
        'data_formats' => 'array',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function riskScores()
    {
        return $this->hasMany(RiskScore::class);
    }

    public function riskWeightSettings()
    {
        return $this->hasMany(RiskWeightSetting::class);
    }

    /**
     * Calculate total risk score for this ROPA record
     */
    public function calculateRiskScore()
    {
        $weights = $this->riskWeightSettings()->pluck('weight', 'field_name')->toArray();

        $totalWeight = array_sum($weights);
        if ($totalWeight <= 0) {
            return 0; // Avoid division by zero if no weights exist
        }

        $score = 0;

        foreach ($weights as $field => $weight) {
            if (isset($this->$field) && !empty($this->$field)) {
                $score += $weight;
            }
        }

        // Normalize to a percentage (out of 100)
        $normalizedScore = ($score / $totalWeight) * 100;

        return round($normalizedScore, 2);
    }

    /**
     * Check if the ROPA is reviewed
     */
    public function isReviewed(): bool
    {
        return $this->status === self::STATUS_REVIEWED;
    }

    /**
     * Check if the ROPA is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
