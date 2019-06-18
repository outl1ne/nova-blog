<?php

namespace OptimistDigital\NovaBlog;

use Illuminate\Http\Request;

abstract class Template
{
    public static $type = 'post';
    public static $name = '';
    public static $seo = false;

    protected $resource = null;

    public function __construct($resource = null)
    {
        $this->resource = $resource;
    }

    abstract function fields(Request $request): array;
}
