<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Posts table name
    |--------------------------------------------------------------------------
    |
    | Set a custom table for Nova Blog to store its posts data.
    |
    */

    'blog_posts_table' => 'nova_blog_posts',

    /*
    |--------------------------------------------------------------------------
    | Categories table name
    |--------------------------------------------------------------------------
    |
    | Set a custom table for Nova Blog to store its categories data.
    |
    */

    'blog_categories_table' => 'nova_blog_categories',

    /*
    |--------------------------------------------------------------------------
    | Overwrite the post resource with a custom implementation
    |--------------------------------------------------------------------------
    |
    | Add a custom implementation of the Post resource
    |
    */

    'post_resource' => null,

    /*
    |--------------------------------------------------------------------------
    | Page URL
    |--------------------------------------------------------------------------
    |
    | If a closure is specified, a link to the page is shown next to
    | the page slug. The closure accepts a Page model as a paramater.
    |
    | Set to `null` if the link should not be displayed.
    |
    */

    'page_url' => function (\OptimistDigital\NovaBlog\Models\Post $post) {
        // For example:
        // return env('FRONTEND_URL') . $post->slug;
        return null;
    }
];
