<?php

namespace OptimistDigital\NovaBlog\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use OptimistDigital\NovaBlog\Nova\Fields\Slug;
use OptimistDigital\NovaBlog\NovaBlog;

class Category extends TemplateResource
{

    public static $displayInNavigation = false;
    public static $model = null;
    public static $title = 'title';
    public static $search = ['id','title','slug'];

    public function __construct($resource)
    {
        self::$model = NovaBlog::getCategoryModel();
        parent::__construct($resource);
    }

    public static function newModel()
    {
        $model = empty(self::$model) ? NovaBlog::getCategoryModel() : self::$model;

        return new $model;
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('novaBlog.title'), 'title'),
            Slug::make(__('novaBlog.slug'), 'slug')->rules('required', 'alpha_dash_or_slash'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static function label()
    {
        return __('novaBlog.categories');
    }

    public static function singularLabel()
    {
        return __('novaBlog.category');
    }
}
