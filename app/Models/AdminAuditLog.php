<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAuditLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function model()
    {
        return $this->morphTo('model', 'model_type', 'model_id');
    }

    public static function log(string $action, $model = null, array $changes = [], ?int $adminId = null): void
    {
        static::create([
            'admin_id' => $adminId ?? auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'old_values' => $changes['old'] ?? null,
            'new_values' => $changes['new'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logLogin(User $admin): void
    {
        static::log('login', $admin, [], $admin->id);
    }

    public static function logLogout(User $admin): void
    {
        static::log('logout', $admin, [], $admin->id);
    }

    public static function logImpersonate(User $admin, User $target): void
    {
        static::log('impersonate', $target, [
            'target_user' => $target->id,
        ], $admin->id);
    }

    public static function logStopImpersonate(User $admin): void
    {
        static::log('stop_impersonate', null, [], $admin->id);
    }

    public static function logCreate($model, array $data = [], ?int $adminId = null): void
    {
        static::log('create', $model, [
            'new' => $data ?: $model->toArray(),
        ], $adminId);
    }

    public static function logUpdate($model, array $old = [], array $new = [], ?int $adminId = null): void
    {
        static::log('update', $model, [
            'old' => $old ?: $model->getOriginal(),
            'new' => $new ?: $model->toArray(),
        ], $adminId);
    }

    public static function logDelete($model, array $data = [], ?int $adminId = null): void
    {
        static::log('delete', $model, [
            'old' => $data ?: $model->toArray(),
        ], $adminId);
    }

    public function getActionIconAttribute(): string
    {
        $icons = [
            'create' => '➕',
            'update' => '✏️',
            'delete' => '🗑️',
            'approve' => '✅',
            'reject' => '❌',
            'suspend' => '⏸️',
            'activate' => '▶️',
            'ban' => '🚫',
            'unban' => '🔓',
            'login' => '🔑',
            'logout' => '🚪',
            'impersonate' => '👤',
            'stop_impersonate' => '👥',
            'export' => '📤',
            'import' => '📥',
            'backup' => '💾',
            'restore' => '♻️',
        ];

        return $icons[$this->action] ?? '📝';
    }

    public function getActionColorAttribute(): string
    {
        $colors = [
            'create' => 'green',
            'update' => 'blue',
            'delete' => 'red',
            'approve' => 'green',
            'reject' => 'red',
            'suspend' => 'amber',
            'activate' => 'green',
            'ban' => 'red',
            'unban' => 'green',
            'login' => 'blue',
            'logout' => 'gray',
            'impersonate' => 'purple',
            'stop_impersonate' => 'purple',
            'export' => 'blue',
            'import' => 'blue',
            'backup' => 'green',
            'restore' => 'amber',
        ];

        return $colors[$this->action] ?? 'gray';
    }

    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeForModel($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    public function scopeForDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
