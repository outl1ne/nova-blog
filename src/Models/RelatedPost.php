<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Database\Eloquent\Model;

class RelatedPost extends Model
{
    protected $fillable = [
        'post_id', 'related_post_id'
    ];
    protected $table = 'nova_blog_related_posts';
}
