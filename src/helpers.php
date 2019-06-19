<?php

use OptimistDigital\NovaBlog\Models\Post;
use OptimistDigital\NovaBlog\Models\TemplateModel;
use OptimistDigital\NovaBlog\NovaBlog;

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
// nova_get_post
// ------------------------------

if (!function_exists('nova_get_post')) {

    function nova_get_post($postId)
    {
        if (empty($postId)) return null;
        $post = Post::find($postId);
        if (empty($post)) return null;

        return [
            'id' => $post->id,
            'name' => $post->title,
            'slug' => $post->slug,
            'published_at' => $post->published_at,
            'data' => $post->post_content = json_decode($post->post_content),
        ];
    }
}


// ------------------------------
// nova_resolve_template_field_value
// ------------------------------

if (!function_exists('nova_resolve_template_field_value')) {
    function nova_resolve_template_field_value($field, $fieldValue, $templateModel)
    {
        return method_exists($field, 'resolveResponseValue')
            ? $field->resolveResponseValue($fieldValue, $templateModel)
            : $fieldValue;
    }
}


// ------------------------------
// nova_resolve_template_model_data
// ------------------------------

if (!function_exists('nova_resolve_template_model_data')) {
    function nova_resolve_template_model_data(TemplateModel $templateModel)
    {
        // Find the Template class for the model
        foreach (NovaBlog::getTemplates() as $tmpl) {
            if ($tmpl::$name === $templateModel->template) $templateClass = $tmpl;
        }

        // Fail silently is template is no longer registered
        if (!isset($templateClass)) return null;

        // Get the template's fields
        $fields = collect((new $templateClass($templateModel))->fields(request()));

        $resolvedData = [];
        foreach (((array)$templateModel->data) as $fieldAttribute => $fieldValue) {
            $field = $fields->where('attribute', $fieldAttribute)->first();
            if (!isset($field)) continue;

            if ($field->component === 'nova-flexible-content') {
                $resolvedData[$fieldAttribute] = nova_resolve_flexible_fields_data($field, $fieldValue, $templateModel);
                continue;
            }

            $resolvedData[$fieldAttribute] = nova_resolve_template_field_value($field, $fieldValue, $templateModel);
        }
        return $resolvedData;
    }
}
