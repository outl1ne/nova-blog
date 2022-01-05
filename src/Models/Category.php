<?php

namespace OptimistDigital\NovaBlog\Models;

use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model implements Sortable
{
    use SortableTrait;
    use HasTranslations;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
    ];

    public $translatable = ['title','slug'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('nova-blog.blog_categories_table', 'nova_blog_categories'));
    }

    public function posts(){
        return $this->hasMany(Post::class, 'category_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if ($this->translatable && in_array($field, $this->translatable)) {
            return $this->where($field . '->' . app()->getLocale(), $value)
                ->orWhere($field . '->en', $value)->firstOrFail();
        } else {
            return parent::resolveRouteBinding($value, $field);
        }
    }
}
