<?php

namespace OptimistDigital\NovaBlog\Nova\Flexible\Layouts;

use App\Models\Page;
use Inspheric\Fields\Url;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ButtonLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'button';

    /**
     * The displayed title
     *
     */
    public function title()
    {
        return __('novaBlog.buttonSection');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Text::make(__('novaBlog.title'), 'title'),
            Url::make(__('novaBlog.url'), 'url'),
            Boolean::make(__('novaBlog.newPage'), 'new_page')->default(false),
        ];
    }
}
