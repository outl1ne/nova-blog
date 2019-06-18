<?php

namespace OptimistDigital\NovaBlog\Models;

use OptimistDigital\NovaBlog\NovaBlog;

class Region extends TemplateModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(NovaBlog::getRegionsTableName());
    }
}
