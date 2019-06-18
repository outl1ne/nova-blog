<?php

namespace OptimistDigital\NovaBlog\Nova\Fields;

use Laravel\Nova\Fields\Field;
use OptimistDigital\NovaBlog\NovaBlog;
use OptimistDigital\NovaBlog\Models\Post;
use OptimistDigital\NovaBlog\Models\Region;

class TemplateField extends Field
{
    public $component = 'template-field';

    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $resourceName = rtrim(request()->route('resource'), 's');

        $this->withMeta([
            'asHtml' => true,
            'templates' => collect(NovaBlog::getTemplates())
                ->filter(function ($template) use ($resourceName) {
                    return $template::$type === $resourceName;
                })
                ->map(function ($template) {
                    return [
                        'label' => $template::$name,
                        'value' => $template::$name
                    ];
                }),
            'resourceTemplates' => collect(Post::all(), Region::all())->flatten()->pluck('template', 'id')
        ]);

        $templates = array_map(function ($template) {
            return $template::$name;
        }, NovaBlog::getTemplates());
        $this->rules('required', 'in:' . implode(',', $templates));
    }
}
