<?php
namespace OptimistDigital\NovaBlog\Nova\Fields;

use Laravel\Nova\Fields\Field;
use OptimistDigital\NovaPageManager\NovaPageManager;
use OptimistDigital\NovaPageManager\Models\Region;
use Laravel\Nova\Fields\Text;

class Slug extends Text
{
    public $component = 'slug-field';
}
