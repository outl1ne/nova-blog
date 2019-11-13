<?php

namespace OptimistDigital\NovaBlog;

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Nova;
use Illuminate\Support\ServiceProvider;
use OptimistDigital\NovaBlog\Nova\Post;
use OptimistDigital\NovaBlog\Nova\Category;
use OptimistDigital\NovaBlog\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-blog');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/nova-blog.php' => config_path('nova-blog.php'),
        ], 'config');

        $this->app->booted(function () {
            $this->routes();
        });

        $postResource = config('nova-blog.post_resource') ?: Post::class;
        $categoryResource = config('nova-blog.category_resource') ?: Category::class;

        Nova::resources([
            $postResource,
            $categoryResource
        ]);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }
        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-blog')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
