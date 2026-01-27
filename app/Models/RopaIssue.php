<?php

namespace App\Models;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class RopaIssue extends Model
{
    protected $fillable = [
        'ropa_id',
        'user_id',
        'title',
        'description',
        'risk_level',
        'status',
        'comment',
    ];

    public function ropa()
    {
        return $this->belongsTo(Ropa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
