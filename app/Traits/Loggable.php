<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait Loggable
{
    /**
     * Boot the trait
     */
    protected static function bootLoggable()
    {
        // Log when model is created
        static::created(function ($model) {
            if (auth()->check()) {
                ActivityLog::log('created', $model);
            }
        });

        // Log when model is updated
        static::updated(function ($model) {
            if (auth()->check()) {
                $changes = $model->getChanges();
                $original = $model->getOriginal();

                $properties = [
                    'old' => array_intersect_key($original, $changes),
                    'new' => $changes,
                ];

                ActivityLog::log('updated', $model, null, $properties);
            }
        });

        // Log when model is deleted
        static::deleted(function ($model) {
            if (auth()->check()) {
                ActivityLog::log('deleted', $model);
            }
        });
    }

    /**
     * Get activity logs for this model
     */
    public function activities()
    {
        return ActivityLog::where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->latest()
            ->get();
    }

    /**
     * Log download action
     */
    public function logDownload()
    {
        if (auth()->check()) {
            ActivityLog::log('downloaded', $this);
        }
    }
}