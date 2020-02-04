<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    protected $casts = [
        'published_at' => 'datetime',
        'data' => 'object'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'published_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('nova-blog.blog_posts_table', 'nova_blog_posts'));
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if ($post->is_pinned) {
                Post::where('is_pinned', true)->each(function ($pinnedPost) {
                    $pinnedPost->is_pinned = false;
                    $pinnedPost->save();
                });
            }
            return true;
        });
    }

    public function childDraft()
    {
        return $this->hasOne(Post::class, 'draft_parent_id', 'id');
    }

    public function isDraft()
    {
        return isset($this->preview_token) ? true : false;
    }
}
