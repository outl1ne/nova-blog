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
  'table_categories' => 'nova_blog_categories',

  /*
  |--------------------------------------------------------------------------
  | Overwrite the post resource with a custom implementation
  |--------------------------------------------------------------------------
  |
  | Add a custom implementation of the Post resource
  |
  */
  'post_resource' => null,

];
