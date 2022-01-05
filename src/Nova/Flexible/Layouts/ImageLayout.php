<?php

namespace OptimistDigital\NovaBlog\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Image;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ImageLayout extends Layout
{

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'image';

    /**
     * The displayed title
     */
    public function title(){
        return __('novaBlog.imageSection');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Image::make(__('novaBlog.image'), 'image')->deletable(false)->creationRules('required'),
            Text::make(__('novaBlog.imageCaption'), 'caption'),
            Text::make(__('novaBlog.imageAlt'), 'alt')
        ];
    }
}
