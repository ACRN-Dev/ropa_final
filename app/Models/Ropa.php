<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use App\Models\User;
use App\Models\EnterpriseRisk;

class Ropa extends Model
{
    use HasFactory;

    const STATUS_PENDING  = 'Pending';
    const STATUS_REVIEWED = 'Reviewed';

    const RISK_LEVELS = ['low', 'medium', 'high', 'critical'];

    protected $fillable = [
        'user_id',
        'status',
        'risk_level',
        'organisation_name',
        'other_organisation_name',
        'department',
        'other_department',
        'processes',
        'data_sources',
        'data_sources_other',
        'data_formats',
        'data_formats_other',
        'information_nature',
        'personal_data_categories',
        'personal_data_categories_other',
        'records_count',
        'data_volume',
        'retention_period_years',
        'access_estimate',
        'retention_rationale',
        'information_shared',
        'sharing_local',
        'sharing_transborder',
        'sharing_type',
        'local_organizations',
        'transborder_countries',
        'sharing_comment',
        'access_control',
        'access_measures',
        'technical_measures',
        'technical_measures_other',
        'organisational_measures',
        'organisational_measures_other',
        'lawful_basis',
        'lawful_basis_other',
        'risk_report',
    ];

    protected $casts = [
        'processes'                      => 'array',
        'data_sources'                   => 'array',
        'data_sources_other'             => 'array',
        'data_formats'                   => 'array',
        'data_formats_other'             => 'array',
        'information_nature'             => 'array',
        'personal_data_categories'       => 'array',
        'personal_data_categories_other' => 'array',
        'records_count'                  => 'array',
        'data_volume'                    => 'array',
        'retention_period_years'         => 'array',
        'access_estimate'                => 'array',
        'retention_rationale'            => 'array',
        'local_organizations'            => 'array',
        'transborder_countries'          => 'array',
        'sharing_type'                   => 'array',
        'access_measures'                => 'array',
        'technical_measures'             => 'array',
        'technical_measures_other'       => 'array',
        'organisational_measures'        => 'array',
        'organisational_measures_other'  => 'array',
        'lawful_basis'                   => 'array',
        'lawful_basis_other'             => 'array',
        'risk_report'                    => 'array',
        'information_shared'             => 'boolean',
        'sharing_local'                  => 'boolean',
        'sharing_transborder'            => 'boolean',
        'access_control'                 => 'boolean',
        'archived'                       => 'boolean',
        'risk_level'                     => 'string',
        'date_submitted'                 => 'datetime',
    ];

    // ---------------------------------------------------
    // Relationships
    // ---------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function enterpriseRisks()
    {
        return $this->hasMany(EnterpriseRisk::class, 'source_id')
                    ->where('source_type', 'ROPA');
    }

    public function openRisks()
    {
        return $this->enterpriseRisks()->where('status', 'open');
    }

    public function highPriorityRisks()
    {
        return $this->enterpriseRisks()->whereIn('risk_level', ['high', 'critical']);
    }

    // ---------------------------------------------------
    // Status helpers
    // ---------------------------------------------------

    public function isReviewed(): bool
    {
        return $this->status === self::STATUS_REVIEWED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function hasRisks(): bool
    {
        return $this->enterpriseRisks()->exists();
    }

    /**
     * Whether a risk level has been assigned to this ROPA.
     */
    public function hasRiskLevel(): bool
    {
        return !is_null($this->risk_level);
    }

    // ---------------------------------------------------
    // Counts
    // ---------------------------------------------------

    public function getRisksCountAttribute(): int
    {
        return $this->enterpriseRisks()->count();
    }

    public function getOpenRisksCountAttribute(): int
    {
        return $this->openRisks()->count();
    }

    // ---------------------------------------------------
    // Risk scoring (based on stored risk_level column)
    // ---------------------------------------------------

    public function calculateRiskScore(): int
    {
        return match ($this->risk_level) {
            'critical' => 4,
            'high'     => 3,
            'medium'   => 2,
            'low'      => 1,
            default    => 0,
        };
    }

    // ---------------------------------------------------
    // Sections (for risk scoring / reporting)
    // ---------------------------------------------------

    public static function sections(): array
    {
        return [
            'organisation_name',
            'department',
            'processes',
            'data_sources',
            'data_formats',
            'information_nature',
            'personal_data_categories',
            'records_count',
            'data_volume',
            'retention_period_years',
            'access_estimate',
            'retention_rationale',
            'information_shared',
            'sharing_type',
            'local_organizations',
            'transborder_countries',
            'access_control',
            'access_measures',
            'technical_measures',
            'technical_measures_other',
            'organisational_measures',
            'organisational_measures_other',
            'lawful_basis',
            'lawful_basis_other',
            'risk_report',
        ];
    }

    // ---------------------------------------------------
    // Convenience accessors
    // ---------------------------------------------------

    public function getOtherOrganisationNamesAttribute(): array
    {
        return $this->other_organisation_name ?? [];
    }

    public function getOtherDepartmentNamesAttribute(): array
    {
        return $this->other_department ?? [];
    }

    public function getTechnicalMeasuresOtherAttribute(): array
    {
        return $this->technical_measures_other ?? [];
    }

    public function getOrganisationalMeasuresOtherAttribute(): array
    {
        return $this->organisational_measures_other ?? [];
    }

    public function getLawfulBasisOtherAttribute(): array
    {
        return $this->lawful_basis_other ?? [];
    }
}
