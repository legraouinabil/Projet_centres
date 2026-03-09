<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\ActivityLogger;
use App\Models\ActivityLog;

class GenericModelObserver
{
    protected function shouldIgnore(Model $model)
    {
        return $model instanceof ActivityLog || (method_exists($model, 'getTable') && $model->getTable() === 'activity_logs');
    }

    public function created(Model $model)
    {
        if ($this->shouldIgnore($model)) return;
        ActivityLogger::log('model.created', $model, ['attributes' => $model->getAttributes()]);
    }

    public function updated(Model $model)
    {
        if ($this->shouldIgnore($model)) return;
        $changes = $model->getChanges();
        $original = [];
        foreach (array_keys($changes) as $key) {
            $original[$key] = $model->getOriginal($key);
        }
        ActivityLogger::log('model.updated', $model, ['changes' => $changes, 'original' => $original]);
    }

    public function deleted(Model $model)
    {
        if ($this->shouldIgnore($model)) return;
        ActivityLogger::log('model.deleted', $model, ['attributes' => $model->getAttributes()]);
    }

    public function restored(Model $model)
    {
        if ($this->shouldIgnore($model)) return;
        ActivityLogger::log('model.restored', $model, ['attributes' => $model->getAttributes()]);
    }

    public function forceDeleted(Model $model)
    {
        if ($this->shouldIgnore($model)) return;
        ActivityLogger::log('model.forceDeleted', $model, ['attributes' => $model->getAttributes()]);
    }
}
