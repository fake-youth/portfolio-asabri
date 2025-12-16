<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Log activity
     */
    public static function log(string $action, Model $model, ?string $description = null, ?array $properties = null)
    {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $description ?? "{$action} {$model->judul}",
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get action badge color
     */
    public function getActionBadgeAttribute()
    {
        $badges = [
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'downloaded' => 'info',
        ];

        return $badges[$this->action] ?? 'secondary';
    }

    /**
     * Get action label
     */
    public function getActionLabelAttribute()
    {
        $labels = [
            'created' => 'Upload',
            'updated' => 'Update',
            'deleted' => 'Hapus',
            'downloaded' => 'Download',
        ];

        return $labels[$this->action] ?? $this->action;
    }
}