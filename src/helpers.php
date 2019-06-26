<?php

use OptimistDigital\NovaBlog\Models\Post;


// ------------------------------
// nova_get_posts_structure
// ------------------------------

if (!function_exists('nova_get_blog_structure')) {
    function nova_get_blog_structure()
    {
        return Post::all()->map(function ($post) {
            $post->post_content = json_decode($post->post_content);
            return $post;
        });
    }
}

// ------------------------------
// nova_get_post_by_slug
// ------------------------------

if (!function_exists('nova_get_post_by_slug')) {

    function nova_get_post_by_slug($slug)
    {
        if (empty($slug)) return null;
        $post = Post::where('slug', $slug)->firstOrFail();
        if (empty($post)) return null;

        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'published_at' => $post->published_at,
            'post_content' => $post->post_content = json_decode($post->post_content),
        ];
    }
}

// ------------------------------
// nova_get_post
// ------------------------------

if (!function_exists('nova_get_post_by_id')) {

    function nova_get_post_by_id($postId)
    {
        if (empty($postId)) return null;
        $post = Post::find($postId);
        if (empty($post)) return null;

        return [
            'id' => $post->id,
            'name' => $post->title,
            'slug' => $post->slug,
            'published_at' => $post->published_at,
            'post-content' => $post->post_content = json_decode($post->post_content),
        ];
    }
}
