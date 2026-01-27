<?php

namespace App\Models;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ropa;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    const STATUS_PENDING     = 'Pending';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_REVIEWED    = 'Reviewed';

    protected $fillable = [
        'ropa_id',
        'user_id',
        'comment',
        'data_processing_agreement_file',
        'data_protection_dpi_file',
        'data_sharing_agreement',
        'risks',
        'mitigation_measures',
        'overall_risk_score',
        'impact_level',
        'children_data_transfer',
        'vulnerable_population_transfer',
        'section_scores',
        'status', // ⭐ ADDED
    ];

    protected $casts = [
        'section_scores' => 'array',
        'risks' => 'array',
        'children_data_transfer' => 'boolean',
        'vulnerable_population_transfer' => 'boolean',
    ];

    /** Relationships */
    public function ropa()
    {
        return $this->belongsTo(Ropa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** -------------------------
     * SECTION SCORE CALCULATIONS
     * ------------------------- */

    public function getTotalScoreAttribute()
    {
        if (!is_array($this->section_scores)) {
            return 0;
        }

        return array_sum(array_filter($this->section_scores, 'is_numeric'));
    }

    public function getAverageScoreAttribute()
    {
        if (!is_array($this->section_scores)) {
            return 0;
        }

        $scores = array_filter($this->section_scores, 'is_numeric');

        if (count($scores) === 0) {
            return 0;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    /** -------------------------
     * RISK SCORING CALCULATIONS
     * ------------------------- */

    public function getCalculatedOverallRiskScoreAttribute()
    {
        if (!is_array($this->risks) || count($this->risks) === 0) {
            return 0;
        }

        $riskScores = array_map(function ($risk) {
            $prob = isset($risk['probability']) ? (int)$risk['probability'] : 1;
            $impact = isset($risk['impact']) ? (int)$risk['impact'] : 1;
            return $prob * $impact;
        }, $this->risks);

        $totalScore = array_sum($riskScores);
        $maxScore = count($this->risks) * 25; // 5 × 5

        if ($maxScore === 0) {
            return 0;
        }

        return round(($totalScore / $maxScore) * 100);
    }

    public function getCalculatedImpactLevelAttribute()
    {
        $percent = $this->calculated_overall_risk_score;

        return match (true) {
            $percent <= 20 => 'Low',
            $percent <= 60 => 'Medium',
            $percent <= 80 => 'High',
            default        => 'Critical',
        };
    }

    /** -------------------------
     * STATUS HANDLING
     * ------------------------- */

    // Default value helper
    public function getStatusAttribute($value)
    {
        return $value ?? self::STATUS_PENDING;
    }
}
