<?php

namespace OptimistDigital\NovaBlog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use OptimistDigital\NovaBlog\NovaBlog;
use OptimistDigital\NovaBlog\Nova\Fields\ParentField;
use OptimistDigital\NovaBlog\Nova\Fields\TemplateField;
use OptimistDigital\NovaLocaleField\LocaleField;

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
            Text::make('Name', 'name')->rules('required'),
            Text::make('Slug', 'slug')
                ->creationRules('required', "unique:{$tableName},slug,NULL,id,locale,$request->locale")
                ->updateRules('required', "unique:{$tableName},slug,{{resourceId}},id,locale,$request->locale"),
            ParentField::make('Parent', 'parent_id'),
            TemplateField::make('Template', 'template'),
            LocaleField::make('Locale', 'locale', 'locale_parent_id')
                ->locales(NovaBlog::getLocales())
                ->maxLocalesOnIndex(config('nova-blog.max_locales_shown_on_index', 4))
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
