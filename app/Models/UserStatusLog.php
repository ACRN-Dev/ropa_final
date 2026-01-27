<?php

namespace App\Models;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatusLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
