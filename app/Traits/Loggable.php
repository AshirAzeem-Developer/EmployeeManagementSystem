<?php

namespace App\Traits;

use App\Helpers\LogActivity;

trait Loggable
{
    public static function bootLoggable()
    {
        static::created(function ($model) {
            $modelName = class_basename($model);
            LogActivity::addToLog("Create $modelName", "Created new $modelName with ID: {$model->id}");
        });

        static::updated(function ($model) {
            $modelName = class_basename($model);
            $changes = $model->getChanges();
            unset($changes['updated_at']); // Ignore updated_at timestamp change
            
            if (empty($changes)) {
                return;
            }

            $changedFields = implode(', ', array_keys($changes));
            LogActivity::addToLog("Update $modelName", "Updated $modelName ID: {$model->id}. Changed fields: $changedFields");
        });

        static::deleted(function ($model) {
            $modelName = class_basename($model);
            LogActivity::addToLog("Delete $modelName", "Deleted $modelName with ID: {$model->id}");
        });
    }
}
