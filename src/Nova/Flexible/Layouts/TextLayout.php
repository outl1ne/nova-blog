<?php

namespace OptimistDigital\NovaBlog\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Trix;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class TextLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'text';

    /**
     * The displayed title
     */
    public function title(){
        return __('novaBlog.textSection');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            config('nova-blog.use_trix') === true ? Trix::make(__('novaBlog.textContent'), 'text_content') : Markdown::make('Testo', 'text_content'),
        ];
    }
}
