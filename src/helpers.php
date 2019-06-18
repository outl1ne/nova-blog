<?php

use OptimistDigital\NovaBlog\Models\Post;
use OptimistDigital\NovaBlog\Models\Region;
use Illuminate\Support\Collection;
use OptimistDigital\NovaBlog\Models\TemplateModel;
use OptimistDigital\NovaBlog\NovaBlog;

// ------------------------------
// nova_get_posts_structure
// ------------------------------

if (!function_exists('nova_get_blog_structure')) {
    function nova_get_blog_structure()
    {
        $formatPosts = function (Collection $posts) use (&$formatPosts) {
            $data = [];
            $posts->each(function ($post) use (&$data, &$formatPosts) {
                $localeChildren = Post::where('locale_parent_id', $post->id)->get();
                $_posts = collect([$post, $localeChildren])->flatten();
                $_data = [
                    'locales' => $_posts->pluck('locale'),
                    'id' => $_posts->pluck('id', 'locale'),
                    'name' => $_posts->pluck('name', 'locale'),
                    'slug' => $_posts->pluck('slug', 'locale'),
                    'template' => $post->template,
                ];

                $children = Post::where('parent_id', $post->id)->get();
                if ($children->count() > 0) {
                    $_data['children'] = $formatPosts($children);
                }

                $data[] = $_data;
            });
            return $data;
        };

        $parentPosts = Post::whereNull('parent_id')->whereNull('locale_parent_id')->get();
        return $formatPosts($parentPosts);
    }
}


// ------------------------------
// nova_get_regions
// ------------------------------

if (!function_exists('nova_get_regions')) {
    function nova_get_regions()
    {
        $formatRegions = function (Collection $regions) {
            $data = [];
            $regions->each(function ($region) use (&$data) {
                $localeChildren = Region::where('locale_parent_id', $region->id)->get();
                $_regions = collect([$region, $localeChildren])->flatten();
                $data[] = [
                    'locales' => $_regions->pluck('locale'),
                    'id' => $_regions->pluck('id', 'locale'),
                    'name' => $_regions->pluck('name', 'locale'),
                    'template' => $region->template,
                    'data' => $_regions->map(function ($_region) {
                        return [
                            'locale' => $_region->locale,
                            'data' => nova_resolve_template_model_data($_region),
                        ];
                    })->pluck('data', 'locale'),
                ];
            });
            return $data;
        };

        $parentRegions = Region::whereNull('locale_parent_id')->get();
        return $formatRegions($parentRegions);
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
            'locale' => $post->locale,
            'id' => $post->id,
            'name' => $post->name,
            'slug' => $post->slug,
            'data' => nova_resolve_template_model_data($post),
            'template' => $post->template,
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


// ------------------------------
// nova_resolve_flexible_fields_data
// ------------------------------

if (!function_exists('nova_resolve_flexible_fields_data')) {
    function nova_resolve_flexible_fields_data($field, $flexibleFieldValue, $templateModel)
    {
        // Accessing protected property helper
        $accessProtectedProperty = function ($object, $property) {
            $reflection = new ReflectionClass($object);
            $_property = $reflection->getProperty($property);
            $_property->setAccessible(true);
            return $_property->getValue($object);
        };

        $flexibleLayouts = $accessProtectedProperty($field, 'layouts');

        $resolvedData = [];
        foreach ($flexibleFieldValue as $layoutValue) {
            foreach ($flexibleLayouts as $layout) {
                $layoutName = $accessProtectedProperty($layout, 'name');
                if ($layoutName !== $layoutValue->layout) continue;

                $layoutFields = $accessProtectedProperty($layout, 'fields');

                $resolvedLayoutData = [
                    'layout' => $layoutName,
                    'attributes' => [],
                ];

                foreach ($layoutValue->attributes as $fieldAttribute => $fieldValue) {
                    $subField = $layoutFields->where('attribute', $fieldAttribute)->first();
                    if (!isset($subField)) continue;
                    $resolvedLayoutData['attributes'][$fieldAttribute] = nova_resolve_template_field_value($subField, $fieldValue, $templateModel);
                }

                $resolvedData[] = $resolvedLayoutData;
            }
        }
        return $resolvedData;
    }
}
