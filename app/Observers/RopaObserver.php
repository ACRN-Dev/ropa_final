<?php

namespace App\Observers;

use App\Models\Ropa;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Request;

class RopaObserver
{
    public function created(Ropa $ropa): void
    {
        UserActivity::create([
            'user_id'     => auth()->id(),
            'action'      => 'created',
            'model'       => 'ropa',
            'model_type'  => Ropa::class,
            'model_id'    => $ropa->id,
            'description' => (auth()->user()->name ?? 'Unknown') . ' created ROPA record #' . $ropa->id
                             . ($ropa->organisation_name ? ' — ' . $ropa->organisation_name : ''),
            'new_values'  => $this->safeAttributes($ropa),
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }

    public function updated(Ropa $ropa): void
    {
        $changed = $ropa->getDirty();

        // Skip if only timestamps changed
        unset($changed['updated_at']);
        if (empty($changed)) {
            return;
        }

        UserActivity::create([
            'user_id'     => auth()->id(),
            'action'      => 'updated',
            'model'       => 'ropa',
            'model_type'  => Ropa::class,
            'model_id'    => $ropa->id,
            'description' => (auth()->user()->name ?? 'Unknown') . ' updated ROPA record #' . $ropa->id
                             . ($ropa->organisation_name ? ' — ' . $ropa->organisation_name : ''),
            'old_values'  => array_intersect_key($ropa->getOriginal(), $changed),
            'new_values'  => $changed,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }

    public function deleted(Ropa $ropa): void
    {
        UserActivity::create([
            'user_id'     => auth()->id(),
            'action'      => 'deleted',
            'model'       => 'ropa',
            'model_type'  => Ropa::class,
            'model_id'    => $ropa->id,
            'description' => (auth()->user()->name ?? 'Unknown') . ' deleted ROPA record #' . $ropa->id
                             . ($ropa->organisation_name ? ' — ' . $ropa->organisation_name : ''),
            'old_values'  => $this->safeAttributes($ropa),
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }

    private function safeAttributes(Ropa $ropa): array
    {
        // Exclude large/binary fields from being stored
        return array_diff_key(
            $ropa->getAttributes(),
            array_flip(['password', 'remember_token'])
        );
    }
}