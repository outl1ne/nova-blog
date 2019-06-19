<?php

namespace OptimistDigital\NovaBlog\Models;

use OptimistDigital\NovaBlog\NovaBlog;

class Post extends TemplateModel
{
    protected $casts = [
        'published_at' => 'datetime'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(NovaBlog::getPostsTableName());
    }

    protected static function boot()
    {
        parent::boot();
    }
}
