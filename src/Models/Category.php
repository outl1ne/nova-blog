<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Database\Eloquent\Model;
use OptimistDigital\NovaBlog\NovaBlog;

class Category extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(NovaBlog::getCategoriesTableName());
    }
}
