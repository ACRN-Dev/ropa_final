<?php

namespace App\Traits;

use App\Services\ActivityLoggerService;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            ActivityLoggerService::log('created', $model, [
                'description' => ucfirst(class_basename($model)) . ' created',
                'new_values'  => $model->getAttributes(),
            ]);
        });

        static::updated(function ($model) {
            $changes  = $model->getChanges();
            $original = array_intersect_key(
                $model->getOriginal(),
                $changes
            );

            if (!empty($changes)) {
                ActivityLoggerService::log('updated', $model, [
                    'description' => ucfirst(class_basename($model)) . ' updated',
                    'old_values'  => $original,
                    'new_values'  => $changes,
                ]);
            }
        });

        static::deleted(function ($model) {
            ActivityLoggerService::log('deleted', $model, [
                'description' => ucfirst(class_basename($model)) . ' deleted',
                'old_values'  => $model->getAttributes(),
            ]);
        });
    }
}
