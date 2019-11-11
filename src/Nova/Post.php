<?php

namespace OptimistDigital\NovaBlog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use OptimistDigital\NovaBlog\NovaBlog;
use Whitecube\NovaFlexibleContent\Flexible;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\DateTime;
use OptimistDigital\NovaBlog\Nova\Fields\Slug;
use OptimistDigital\NovaBlog\Nova\Fields\Title;

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
            Title::make('Title', 'title')->rules('required')->alwaysShow(),
            Boolean::make('Is pinned', 'is_pinned'),
            Slug::make('Slug', 'slug')->rules('required'),
            DateTime::make('Published at', 'published_at')->rules('required'),
            TextArea::make('Post introduction', 'post_introduction'),
            BelongsTo::make('Category', 'category', 'OptimistDigital\NovaBlog\Nova\Category')->nullable(),

            Flexible::make('Post content', 'post_content')->hideFromIndex()
                ->addLayout('Text section', 'text', [
                    Markdown::make('Text content', 'text_content'),
                ])
                ->addLayout('Image section', 'image', [
                    Image::make('Image', 'image')->deletable(false),
                    Text::make('Image caption', 'caption'),
                    Text::make('Alt (image alternate text)', 'alt')
                ])
                ->addLayout('Other embed media section', 'other_media', [
                    Textarea::make('Embed media code (twitter, iframe, etc.)', 'media_code'),
                    Text::make('Media caption', 'caption')
                ])
        ];

        if (class_exists('\OptimistDigital\NovaLang\NovaLang')) {
            $fields[] = \OptimistDigital\NovaLang\NovaLangField\NovaLangField::make('Locale', 'locale');
        }

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
            Text::make('SEO Title', 'seo_title')->hideFromIndex(),
            Text::make('SEO Description', 'seo_description')->hideFromIndex(),
            Image::make('SEO Image', 'seo_image')->hideFromIndex(),
        ];
    }

    public function title()
    {
        return $this->name . ' (' . $this->slug . ')';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        $table = NovaBlog::getPostsTableName() . '.locale';
        if (class_exists('\OptimistDigital\NovaLang\NovaLang'))
            $query->where($table, nova_lang_get_active_locale())
                  ->orWhereNotIn($table, array_keys(nova_lang_get_all_locales()));
        return $query;
    }
}
