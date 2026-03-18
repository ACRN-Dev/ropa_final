<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Request;

class UserObserver
{
    public function created(User $user): void
    {
        // Don't log if no authenticated user (e.g. seeder)
        if (!auth()->check()) {
            return;
        }

        UserActivity::create([
            'user_id'     => auth()->id(),
            'action'      => 'created',
            'model'       => 'user',
            'model_type'  => User::class,
            'model_id'    => $user->id,
            'description' => (auth()->user()->name ?? 'Unknown') . ' created user account: ' . $user->name . ' (' . $user->email . ')',
            'new_values'  => [
                'name'       => $user->name,
                'email'      => $user->email,
                'department' => $user->department,
                'user_type'  => $user->user_type,
            ],
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }

    public function updated(User $user): void
    {
        if (!auth()->check()) {
            return;
        }

        $changed = $user->getDirty();

        // Never log password changes in plain text
        unset($changed['password'], $changed['remember_token'], $changed['updated_at']);

        if (empty($changed)) {
            return;
        }

        UserActivity::create([
            'user_id'     => auth()->id(),
            'action'      => 'updated',
            'model'       => 'user',
            'model_type'  => User::class,
            'model_id'    => $user->id,
            'description' => (auth()->user()->name ?? 'Unknown') . ' updated user account: ' . $user->name,
            'old_values'  => array_intersect_key($user->getOriginal(), $changed),
            'new_values'  => $changed,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}