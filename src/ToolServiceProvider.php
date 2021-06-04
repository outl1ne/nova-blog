<?php

namespace OptimistDigital\NovaBlog;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use OptimistDigital\NovaBlog\Nova\Category;
use OptimistDigital\NovaBlog\Nova\Post;
use OptimistDigital\NovaTranslationsLoader\LoadsNovaTranslations;

class ToolServiceProvider extends ServiceProvider
{
    use LoadsNovaTranslations;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-blog');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadTranslations(__DIR__ . '/../resources/lang', 'nova-blog', true);

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config/nova-blog.php' => config_path('nova-blog.php'),
        ], 'config');

        $postResource = config('nova-blog.post_resource') ?: Post::class;
        $categoryResource = config('nova-blog.category_resource') ?: Category::class;

        Nova::resources([
            $postResource,
            $categoryResource
        ]);

        // Custom validation
        Validator::extend('alpha_dash_or_slash', function ($attribute, $value, $parameters, $validator) {
            if (!is_string($value) && !is_numeric($value)) return false;
            if ($value === '/') return true;
            return preg_match('/^[\pL\pM\pN_-]+$/u', $value) > 0;
        }, 'Field must be alphanumeric with dashes or underscores or a single slash.');
    }
}
