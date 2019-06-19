<?php

namespace OptimistDigital\NovaBlog;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

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
        Nova::script('nova-blog', __DIR__ . '/../dist/js/blog-tool.js');
        Nova::script('nova-template-field', __DIR__ . '/../dist/js/template-field.js');
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


    public static function getPostsTableName(): string
    {
        return config('nova-blog.table', 'nova_blog') . '_posts';
    }
}
