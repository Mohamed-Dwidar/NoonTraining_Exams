<?php

namespace Modules\TypingTestModule\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TypingTestModuleServiceProvider extends ServiceProvider {
    protected string $name = 'TypingTestModule';

    protected string $nameLower = 'typingtestmodule';

    public function boot(): void {
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
        $this->loadViewsFrom(module_path($this->name, 'resources/views'), $this->nameLower);

        if (!$this->app->routesAreCached()) {
            Route::middleware('web')->group(module_path($this->name, 'routes/web.php'));
        }
    }
}
