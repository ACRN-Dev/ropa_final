<?php

namespace App\Observers;

use App\Models\Ropa;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class RopaObserver
{
    public function created(Ropa $ropa)
    {
        if (Auth::check()) {
            UserActivity::create([
                'user_id' => Auth::id(),
                'action' => 'Created a ROPA record',
                'model_type' => Ropa::class,
                'model_id' => $ropa->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        }
    }

    public function updated(Ropa $ropa)
    {
        if (Auth::check()) {
            UserActivity::create([
                'user_id' => Auth::id(),
                'action' => 'Updated a ROPA record',
                'model_type' => Ropa::class,
                'model_id' => $ropa->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        }
    }

    public function deleted(Ropa $ropa)
    {
        if (Auth::check()) {
            UserActivity::create([
                'user_id' => Auth::id(),
                'action' => 'Deleted a ROPA record',
                'model_type' => Ropa::class,
                'model_id' => $ropa->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
            ]);
        }
    }
}
