<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ropa_id',
        'field_name',
        'score',
        'remarks',
    ];

    /**
     * The relationship: a RiskScore belongs to a single ROPA record.
     */
    public function ropa()
    {
        return $this->belongsTo(Ropa::class);
    }

    /**
     * Helper: Determine a qualitative risk level (optional).
     * You can use this in reports or dashboards.
     */
    public function getRiskLevelAttribute()
    {
        if ($this->score >= 15) {
            return 'High';
        } elseif ($this->score >= 8) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    /**
     * Helper: Color label for UI (Bootstrap-based).
     */
    public function getRiskColorAttribute()
    {
        return match ($this->risk_level) {
            'High' => 'danger',
            'Medium' => 'warning',
            default => 'success',
        };
    }
}
