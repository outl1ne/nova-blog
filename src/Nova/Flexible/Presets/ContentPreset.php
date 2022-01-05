<?php

namespace OptimistDigital\NovaBlog\Nova\Flexible\Presets;

use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class ContentPreset extends Preset
{
    /**
     * Execute the preset configuration
     *
     * @return void
     */
    public function handle(Flexible $field)
    {
        $field->button(__('novaBlog.addLayoutButton'));
        $field->fullWidth();
        $field->addLayout(\OptimistDigital\NovaBlog\Nova\Flexible\Layouts\TextLayout::class);
        $field->addLayout(\OptimistDigital\NovaBlog\Nova\Flexible\Layouts\ImageLayout::class);
        $field->addLayout(\OptimistDigital\NovaBlog\Nova\Flexible\Layouts\EmbedMediaLayout::class);
        $field->addLayout(\OptimistDigital\NovaBlog\Nova\Flexible\Layouts\ButtonLayout::class);
    }
}
