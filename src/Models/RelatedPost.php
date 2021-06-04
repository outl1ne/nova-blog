<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedPost extends Model
{
    protected $fillable = [
        'post_id', 'related_post_id'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('nova-blog.blog_related_posts_table', 'nova_blog_related_posts'));
    }

}
