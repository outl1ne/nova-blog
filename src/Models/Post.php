<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Database\Eloquent\Model;
use OptimistDigital\NovaBlog\NovaBlog;

class Post extends Model
{
    public $related_posts;

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

    public function getLocales()
    {
        return [
            'locales' => NovaBlog::getLocales(),
            'active' => $this->locale,
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function relatedPosts()
    {
        return $this->belongsToMany(Post::class, 'nova_blog_related_posts', 'post_id', 'related_post_id');
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
        static::saved(function ($post) {
            RelatedPost::where('post_id', $post->id)->delete();
            collect(json_decode($post->related_posts))->each(function ($postId) use ($post) {
                RelatedPost::create([
                    'post_id' => $post->id,
                    'related_post_id' => $postId
                ]);
            });
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
