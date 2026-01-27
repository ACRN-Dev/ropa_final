<?php
namespace App\Services;

use App\Models\UserActivity;
use Illuminate\Database\Eloquent\Model;

class ActivityLoggerService
{
    public static function log(
        string $action,
        ?Model $model = null,
        array $data = []
    ): void {
        // Auto-generate description if not provided
        $description = $data['description'] ?? (
            $model 
                ? ucfirst($action) . ' ' . class_basename($model)
                : ucfirst($action)
        );

        UserActivity::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'model'      => $model ? class_basename($model) : null,
            'model_id'   => $model?->id,
            'description'=> $description,
            'old_values' => $data['old_values'] ?? null,
            'new_values' => $data['new_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
