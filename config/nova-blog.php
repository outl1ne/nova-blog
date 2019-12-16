<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Table name
  |--------------------------------------------------------------------------
  |
  | Set a custom table for Nova Blog to store its data.
  |
  */

  'table' => 'nova_blog_posts',

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
  | Drafts enabled
  |--------------------------------------------------------------------------
  |
  | If set to true, drafting capabilities will be available.
  |
  */

    'drafts_enabled' => false,

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
