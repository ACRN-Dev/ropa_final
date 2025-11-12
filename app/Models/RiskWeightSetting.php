<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskWeightSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'ropa_id',       
        'field_name',
        'weight',
    ];

    
    public function ropa()
    {
        return $this->belongsTo(Ropa::class);
    }

    
    public static function getWeightsForRopa($ropaId)
    {
        return self::where('ropa_id', $ropaId)->pluck('weight', 'field_name')->toArray();
    }

    
}
