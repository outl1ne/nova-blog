<?php

namespace OptimistDigital\NovaBlog;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use OptimistDigital\NovaBlog\Models\Post;

class NovaBlog extends Tool
{
    private static $templates = [];

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('nova-blog-script', __DIR__ . '/../dist/js/nova-blog-dist.js');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-blog::navigation');
    }

    public static function configure(array $data = [])
    {
        self::$templates = isset($data['templates']) && is_array($data['templates']) ? $data['templates'] : [];
    }


    public static function hasNovaLang(): bool
    {
        return class_exists('\OptimistDigital\NovaLang\NovaLang');
    }

    public static function hasNovaDrafts()
    {
        return class_exists('\OptimistDigital\NovaDrafts\DraftButton') || class_exists('\OptimistDigital\NovaDrafts\PublishedField');
    }

    public static function getCategoryModel(): string
    {
        return config('nova-blog.category_model', \OptimistDigital\NovaBlog\Models\Category::class);
    }

    public static function getPostModel(): string
    {
        return config('nova-blog.post_model', \OptimistDigital\NovaBlog\Models\Post::class);
    }

    public static function getRelatedPostModel(): string
    {
        return config('nova-blog.related_post_model', \OptimistDigital\NovaBlog\Models\RelatedPost::class);
    }

    public static function getPageUrl(Post $post)
    {
        $getPostUrl = config('nova-blog.page_url');
        return isset($getPostUrl) ? call_user_func($getPostUrl, $post) : null;
    }

    public static function getLocales(): array
    {
        $localesConfig = config('nova-blog.locales', ['en' => 'English']);
        if (is_callable($localesConfig)) return call_user_func($localesConfig);
        if (is_array($localesConfig)) return $localesConfig;
        return ['en' => 'English'];
    }
}
