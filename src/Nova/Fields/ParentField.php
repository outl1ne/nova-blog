<?php

namespace OptimistDigital\NovaBlog\Nova\Fields;

use Laravel\Nova\Fields\Field;
use OptimistDigital\NovaBlog\Models\Post;

class ParentField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'parent-field';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, 'parent_id', $resolveCallback);

        $options = [];

        Post
            ::whereNull('locale_parent_id')
            ->get()
            ->each(function ($post) use (&$options) {
                $options[$post->id] = $post->name . ' (' . $post->slug . ')';
            });

        $this->withMeta([
            'asHtml' => true,
            'options' => $options,
        ]);

        $optionKeys = array_keys($options);
        $this->rules('nullable', 'in:' . implode(',', $optionKeys));
    }

    public function resolve($resource, $attribute = null)
    {
        parent::resolve($resource, $attribute);

        $options = $this->meta['options'];

        if (isset($resource->id)) {
            $excluded = $this->findExcludedChildAndParentPosts($resource);
            $excludedIds = array_map(function ($post) {
                return $post['id'];
            }, $excluded);

            $options = array_filter(
                $options,
                function ($key) use ($excludedIds) {
                    return !in_array($key, $excludedIds);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        $parent = null;
        if (isset($resource->parent_id)) {
            $parentPost = Post::find($resource->parent_id);
            $parent = [
                'name' => $parentPost->name,
                'slug' => $parentPost->slug,
            ];
        }

        $this->withMeta([
            'canHaveParent' => empty($resource->locale_parent_id),
            'options' => $options,
            'parent' => $parent,
        ]);
    }

    public function findExcludedChildAndParentPosts($post)
    {
        // Always exclude the current post as being your own parent is a paradox
        $childrenAndParents = [$post];

        // All parent's parents
        if (isset($post->parent_id)) {
            $_current = Post::find($post->parent_id);
            while (isset($_current->parent_id)) {
                $_current = Post::find($_current->parent_id);
                $childrenAndParents[] = $_current;
            }
        }

        // All children
        $childPosts = Post::where('parent_id', $post->id)->get();

        while (sizeof($childPosts) > 0) {
            $childrenAndParents = array_merge($childrenAndParents, $childPosts->toArray());
            $childPosts = Post::whereIn('parent_id', $childPosts->map(function ($childPost) {
                return $childPost->id;
            }))->get();
        }

        return $childrenAndParents;
    }
}
