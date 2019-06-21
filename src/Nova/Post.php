<?php

namespace OptimistDigital\NovaBlog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Panel;
use OptimistDigital\NovaBlog\NovaBlog;
use Whitecube\NovaFlexibleContent\Flexible;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Datetime;
use OptimistDigital\NovaBlog\Nova\Fields\Slug;

class Post extends TemplateResource
{
    public static $title = 'name';
    public static $model = 'OptimistDigital\NovaBlog\Models\Post';
    public static $displayInNavigation = false;

    protected $type = 'post';

    public function fields(Request $request)
    {
        // Get base data
        $tableName = NovaBlog::getPostsTableName();
        $templateClass = $this->getTemplateClass();
        $templateFieldsAndPanels = $this->getTemplateFieldsAndPanels();

        $fields = [
            ID::make()->sortable(),
            Markdown::make('Title', 'title')->rules('required')->alwaysShow(),

            Slug::make('Slug', 'slug'),
            Datetime::make('Published at', 'published_at'),

            Flexible::make('Post content', 'post_content')
                ->addLayout('Text section', 'text', [
                    Markdown::make('Text content', 'text-content'),
                ])
                ->addLayout('Image section', 'image', [
                    Image::make('Image', 'image'),
                    Text::make('Image caption', 'caption')
                ])
                ->addLayout('Video section', 'video', [
                    Text::make('Title'),
                    Image::make('Video thumbnail', 'thumbnail'),
                    Text::make('Video ID (YouTube)', 'video'),
                    Text::make('Video caption', 'caption')
                ])
                ->addLayout('Other embed media section', 'other-media', [
                    Textarea::make('Embed media code (twitter, iframe, etc.)', 'media-code'),
                    Text::make('Media caption', 'caption')
                ])
        ];

        $fields[] = new Panel('SEO', $this->getSeoFields());

        if (count($templateFieldsAndPanels['fields']) > 0) {
            $fields[] = new Panel(
                'Page data',
                array_merge(
                    [Heading::make('Page data')->hideFromDetail()],
                    $templateFieldsAndPanels['fields']
                )
            );
        }
        if (count($templateFieldsAndPanels['panels']) > 0) {
            $fields = array_merge($fields, $templateFieldsAndPanels['panels']);
        }

        return $fields;
    }

    protected function getSeoFields()
    {
        return [
            Heading::make('SEO'),
            Text::make('SEO Title', 'seo_title'),
            Text::make('SEO Description', 'seo_description'),
            Image::make('SEO Image', 'seo_image'),
        ];
    }

    public function title()
    {
        return $this->name . ' (' . $this->slug . ')';
    }
}
