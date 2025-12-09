<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ropa;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'ropa_id',
        'user_id',
        'comment',
        'score',
        'section_scores',
        'data_processing_agreement_file',
        'data_protection_impact_assessment_file',
        'data_sharing_agreement',
        'risks',
        'mitigation_measures',
        'overall_risk_score',
        'impact_level',
        'children_data_transfer',
        'vulnerable_population_transfer',
    ];

    protected $casts = [
        'section_scores' => 'array',
        'risks' => 'array',
        'children_data_transfer' => 'boolean',
        'vulnerable_population_transfer' => 'boolean',
    ];

    /** Review belongs to a ROPA */
    public function ropa()
    {
        return $this->belongsTo(Ropa::class);
    }

    /** Review belongs to a user (admin reviewer) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Review has many comments */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /** Total Score = sum of all section scores */
    public function getTotalScoreAttribute()
    {
        if (!$this->section_scores) {
            return 0;
        }

        return array_sum($this->section_scores);
    }

    /** Average Score = mean of section scores */
    public function getAverageScoreAttribute()
    {
        if (!$this->section_scores || !is_array($this->section_scores)) {
            return null;
        }

        $scores = array_filter($this->section_scores, 'is_numeric');

        if (count($scores) === 0) {
            return 0;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    /** Overall Risk Score as percentage (calculated from risks) */
    public function getCalculatedOverallRiskScoreAttribute()
    {
        if (!$this->risks || !is_array($this->risks)) {
            return 0;
        }

        $totalScore = array_sum(array_map(fn($r) => ($r['probability'] ?? 1) * ($r['impact'] ?? 1), $this->risks));
        $maxScore = count($this->risks) * 5 * 5; // max 5 probability * 5 impact per risk

        return $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;
    }

    /** Calculate Impact Level based on overall risk score */
    public function getCalculatedImpactLevelAttribute()
    {
        $percent = $this->calculated_overall_risk_score;

        if ($percent <= 20) return 'Low';
        if ($percent <= 60) return 'Medium';
        if ($percent <= 80) return 'High';
        return 'Critical';
    }
}
