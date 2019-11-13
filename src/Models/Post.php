<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Support\Str;
use OptimistDigital\NovaBlog\NovaBlog;
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
        $this->setTable(NovaBlog::getPostsTableName());
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
            if (isset($post->draft) && NovaBlog::draftsEnabled()) {
                unset($post['draft']);
                return Post::createDraft($post);
            }
            return true;
        });
    }

    private static function createDraft($postData)
    {
        if (isset($postData->id)) {
            $newPost = $postData->replicate();
            $newPost->published = false;
            $newPost->draft_parent_id = $postData->id;
            $newPost->preview_token = Str::random(20);
            $newPost->save();
            return false;
        }

        $postData->published = false;
        $postData->preview_token = Str::random(20);
        return true;
    }

    public function draftParent()
    {
        return $this->belongsTo(Post::class);
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
