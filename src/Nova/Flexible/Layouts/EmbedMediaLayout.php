<?php

namespace OptimistDigital\NovaBlog\Nova\Flexible\Layouts;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class EmbedMediaLayout extends Layout
{

    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'other_media';

    /**
     * The displayed title
     */
    public function title(){
        return __('novaBlog.embedMediaSection');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Textarea::make(__('novaBlog.embedMediaCode'), 'media_code'),
            Text::make(__('novaBlog.embedMediaCaption'), 'caption')
        ];
    }
}
