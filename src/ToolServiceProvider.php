<?php

namespace OptimistDigital\NovaBlog;

use Laravel\Nova\Nova;
use Illuminate\Support\ServiceProvider;
use OptimistDigital\NovaBlog\Nova\Post;

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

        Nova::resources([
            $postResource
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
    }
}
