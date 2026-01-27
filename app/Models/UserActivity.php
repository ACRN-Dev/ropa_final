<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class UserActivity extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Query Scopes
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }

    public function scopeByModel(Builder $query, string $model): Builder
    {
        return $query->where('model', $model);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accessors
     */
    public function getModelLabelAttribute(): string
    {
        // Show the model name if available, otherwise fallback to 'General'
        return $this->model
            ? str_replace('_', ' ', ucfirst($this->model))
            : 'General';
    }

    public function getActionLabelAttribute(): string
    {
        return ucfirst($this->action);
    }

    public function getDescriptionLabelAttribute(): string
    {
        // Use description if present, otherwise auto-generate
        if ($this->description) {
            return $this->description;
        }

        $modelName = $this->model_label ?? 'record';
        return ucfirst($this->action) . ' ' . $modelName;
    }
}
