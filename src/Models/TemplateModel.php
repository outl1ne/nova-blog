<?php

namespace OptimistDigital\NovaBlog\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateModel extends Model
{
    protected $fillable = ['parent_id', 'locale_parent_id'];

    protected $casts = [
        'data' => 'object'
    ];

    protected static function boot()
    {
        parent::boot();
    }
}
