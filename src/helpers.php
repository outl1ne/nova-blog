<?php

use OptimistDigital\NovaBlog\Models\Post;


// ------------------------------
// nova_get_posts_structure
// ------------------------------

if (!function_exists('nova_get_blog_structure')) {
    function nova_get_blog_structure()
    {
        return Post::with('category')->where('published_at', '<', \DB::raw('NOW()'))->orderBy('published_at', 'desc')->get()->map(function ($post) {
            $post->post_content = nova_blog_map_content(json_decode($post->post_content));

            if ($post->seo_image) {
                $post->seo_image = Storage::disk('public')->url($post->seo_image);
            }

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
        $post = Post::with('category')->where('published_at', '<', \DB::raw('NOW()'))->where('slug', $slug)->firstOrFail();
        if (empty($post)) return null;

        $seo = [
            'title' => $post->seo_title,
            'description' => $post->seo_description,
        ];

        if ($post->seo_image) {
            $imagePath = storage_path('app/public/' . $post->seo_image);
            $imageSize = getimagesize($imagePath);
            $seo['image'] = Storage::disk('public')->url($post->seo_image);
            $seo['image_width'] = $imageSize[0];
            $seo['image_height'] = $imageSize[1];
        }

        return [
            'id' => $post->id,
            'category' => $post->category,
            'title' => $post->title,
            'post_introduction' => $post->post_introduction,
            'slug' => $post->slug,
            'published_at' => $post->published_at,
            'post_content' => nova_blog_map_content(json_decode($post->post_content)),
            'seo' => $seo,
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
        $post = Post::with('category')->where('published_at', '<', \DB::raw('NOW()'))->find($postId);
        if (empty($post)) return null;

        $seo = [
            'title' => $post->seo_title,
            'description' => $post->seo_description,
        ];

        if ($post->seo_image) {
            $imagePath = storage_path('app/public/' . $post->seo_image);
            $imageSize = getimagesize($imagePath);
            $seo['image'] = Storage::disk('public')->url($post->seo_image);
            $seo['image_width'] = $imageSize[0];
            $seo['image_height'] = $imageSize[1];
        }

        return [
            'id' => $post->id,
            'category' => $post->category,
            'title' => $post->title,
            'post_introduction' => $post->post_introduction,
            'slug' => $post->slug,
            'published_at' => $post->published_at,
            'post_content' => nova_blog_map_content(json_decode($post->post_content)),
            'seo' => $seo,
        ];
    }
}

if (!function_exists('nova_blog_map_content')) {
    function nova_blog_map_content($content)
    {
        return collect($content)->map(function ($item) {
            if ($item->layout === 'image') {
                $output = clone $item;
                $output->attributes->image = Storage::disk('public')->url($item->attributes->image);
                return $output;
            }

            return $item;
        });
    }
}
