<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ropa extends Model
{
    use HasFactory;

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
        'lawful_basis',
        'retention_period_years',
        'retention_rationale',
        'users_count',
        'access_control',
        'personal_data_category',
        'organisation_name',
        'department_name',
        'other_department',
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
    ];

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



}
