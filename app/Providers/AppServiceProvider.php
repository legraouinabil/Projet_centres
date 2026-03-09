<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

use App\Models\ActivityLog;
use App\Observers\GenericModelObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-register GenericModelObserver for Eloquent models in app/Models
        $modelsPath = app_path('Models');
        if (is_dir($modelsPath)) {
            foreach (glob($modelsPath . '/*.php') as $file) {
                $class = 'App\\Models\\' . pathinfo($file, PATHINFO_FILENAME);
                if (!class_exists($class)) {
                    continue;
                }
                if (!is_subclass_of($class, \Illuminate\Database\Eloquent\Model::class)) {
                    continue;
                }
                // Skip ActivityLog model to avoid recursion
                if ($class === ActivityLog::class) {
                    continue;
                }

                try {
                    $class::observe(GenericModelObserver::class);
                } catch (\Throwable $e) {
                    // ignore registration errors for specific models
                }
            }
        }
    }
}
