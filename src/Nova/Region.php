<?php

namespace OptimistDigital\NovaBlog\Nova;

use Laravel\Nova\Fields\Text;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Panel;
use OptimistDigital\NovaBlog\Nova\Fields\RegionField;
use OptimistDigital\NovaBlog\NovaBlog;
use OptimistDigital\NovaLocaleField\LocaleField;

class Region extends TemplateResource
{
    public static $title = 'name';
    public static $model = 'OptimistDigital\NovaBlog\Models\Region';
    public static $displayInNavigation = false;

    protected $type = 'region';

    public function fields(Request $request)
    {
        // Get base data
        $templateFields = $this->getTemplateFields();

        // Create fields array
        $fields = [
            ID::make()->sortable(),
            Text::make('Name', 'name')->rules('required'),
            RegionField::make('Region'),
            LocaleField::make('Locale', 'locale', 'locale_parent_id')
                ->locales(NovaBlog::getLocales())
                ->maxLocalesOnIndex(config('nova-blog.max_locales_shown_on_index', 4)),
            new Panel('Region data', $templateFields),
        ];

        return $fields;
    }

    public function title()
    {
        return $this->name;
    }
}
