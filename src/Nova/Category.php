<?php

namespace OptimistDigital\NovaBlog\Nova;

use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use OptimistDigital\NovaBlog\Nova\Fields\Slug;

/**
 * Class Category
 * @package OptimistDigital\NovaBlog\Nova
 * @property \OptimistDigital\NovaBlog\Models\Category $resource
 */
class Category extends TemplateResource
{

    public static $displayInNavigation = false;
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'OptimistDigital\NovaBlog\Models\Category';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {

        $fields = [
            ID::make()->sortable(),
            Text::make('Title', 'title'),
            BelongsTo::make('parent', 'parent', self::class)->exceptOnForms(),
            Select::make('Parent', 'parent_id')->options(\OptimistDigital\NovaBlog\Models\Category::where('id', '!=', $this->resource->id)->get()->pluck('title', 'id'))->onlyOnForms(),
            Number::make('Sort', 'sort'),
            Slug::make('Slug', 'slug'),
            Boolean::make('Visible?', 'visible'),
            TextArea::make('Category introduction', 'category_introduction'),
            TextArea::make('Category content', 'category_content'),
        ];

        $fields[] = new Panel('SEO', $this->getSeoFields());
        return $fields;

    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
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
}
