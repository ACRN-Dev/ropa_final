<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class UserActivity extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',   // fully qualified class name e.g. App\Models\Ropa
        'model',        // short human name e.g. "ropa", "user"
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'meta',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'meta'       => 'array',
        'created_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ─────────────────────────────────────────────────

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

    // ── Accessors ──────────────────────────────────────────────

    public function getModelLabelAttribute(): string
    {
        return $this->model
            ? ucfirst(str_replace('_', ' ', $this->model))
            : 'General';
    }

    public function getActionLabelAttribute(): string
    {
        return ucfirst($this->action);
    }

    public function getDescriptionLabelAttribute(): string
    {
        if ($this->description) {
            return $this->description;
        }
        $modelName = $this->model_label;
        return ucfirst($this->action) . ' ' . $modelName;
    }

    // ── Static helper ─────────────────────────────────────────

    /**
     * Convenience method to log any event from anywhere.
     *
     * UserActivity::log('login', 'auth', null, 'User logged in');
     */
    public static function log(
        string  $action,
        ?string $model      = null,
        ?int    $modelId    = null,
        ?string $description = null,
        array   $oldValues  = [],
        array   $newValues  = [],
        ?string $modelType  = null
    ): self {
        return self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model'       => $model,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $description,
            'old_values'  => $oldValues ?: null,
            'new_values'  => $newValues ?: null,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
        ]);
    }
}