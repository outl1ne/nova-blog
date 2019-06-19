<?php

namespace OptimistDigital\NovaBlog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use OptimistDigital\NovaBlog\NovaBlog;
use Whitecube\NovaFlexibleContent\Flexible;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Datetime;


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
        $templateFields = $this->getTemplateFields();

        $fields = [
            ID::make()->sortable(),
            Text::make('Title', 'title')->rules('required'),
            Text::make('Slug', 'slug'),
            Datetime::make('Published at', 'published_at'),

            Flexible::make('Post content', 'post_content')
                ->addLayout('Post content block', 'post-content-block', [
                    Markdown::make('Block content', 'block-content'),
                ])
                ->addLayout('Video section', 'video', [
                    Text::make('Title'),
                    Image::make('Video thumbnail', 'thumbnail'),
                    Text::make('Video ID (YouTube)', 'video'),
                    Text::make('Video caption', 'caption')
                ])
                ->addLayout('Image section', 'image', [
                    Image::make('Image', 'image'),
                    Text::make('Image caption', 'caption')
                ])
        ];

        if (isset($templateClass) && $templateClass::$seo) $fields[] = new Panel('SEO', $this->getSeoFields());

        $fields[] = new Panel('Post data', $templateFields);

        return $fields;
    }

    protected function getSeoFields()
    {
        return [
            Heading::make('SEO')->hideFromIndex()->hideWhenCreating()->hideFromDetail(),
            Text::make('SEO Title', 'seo_title')->hideFromIndex()->hideWhenCreating(),
            Text::make('SEO Description', 'seo_description')->hideFromIndex()->hideWhenCreating(),
            Image::make('SEO Image', 'seo_image')->hideFromIndex()->hideWhenCreating()
        ];
    }

    public function title()
    {
        return $this->name . ' (' . $this->slug . ')';
    }
}
