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
    | Related posts table name
    |--------------------------------------------------------------------------
    |
    | Set a custom table for Nova Blog to store its posts data.
    |
    */

    'blog_related_posts_table' => 'nova_blog_related_posts',

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
    | the page slug. The closure accepts a Page model as a parameter.
    |
    | Set to `null` if the link should not be displayed.
    |
    */

    'page_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Set all the available locales as [key => name] pairs.
    |
    | For example ['en_US' => 'English'].
    |
    */

    'locales' => ['en' => 'English'],

    /*
    |--------------------------------------------------------------------------
    | Overwrite the category model with a custom implementation
    |--------------------------------------------------------------------------
    |
    | Add a custom implementation of the Category model.
    |
    */

    'category_model' => \OptimistDigital\NovaBlog\Models\Category::class,

    /*
    |--------------------------------------------------------------------------
    | Overwrite the post model with a custom implementation
    |--------------------------------------------------------------------------
    |
    | Add a custom implementation of the Post model.
    |
    */

    'post_model' => \OptimistDigital\NovaBlog\Models\Post::class,

    /*
    |--------------------------------------------------------------------------
    | Overwrite the related post model with a custom implementation
    |--------------------------------------------------------------------------
    |
    | Add a custom implementation of the RelatedPost model.
    |
    */

    'related_post_model' => \OptimistDigital\NovaBlog\Models\RelatedPost::class,

    /*
    |--------------------------------------------------------------------------
    | Overwrite the content preset with a custom implementation
    |--------------------------------------------------------------------------
    |
    | Add a custom implementation of the Content preset.
    |
    */

    'content_preset' => \OptimistDigital\NovaBlog\Nova\Flexible\Presets\ContentPreset::class,


    'hide_pinned_post_option' => false,

    'hide_category_selector' => false,

    'include_featured_image' => false,

    'use_trix' => false,

    'include_include_in_bloglist' => false,

    'include_froala_texteditor_option' => false,

    'include_related_posts_feature' => false,

    'hide_category_column_from_index' => false,

    'hide_related_posts_column_from_index' => false,

    'hide_locale_column_from_index' => false

];
