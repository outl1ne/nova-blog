<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('nova-blog.blog_categories_table', 'nova_blog_categories'));
    }
}
