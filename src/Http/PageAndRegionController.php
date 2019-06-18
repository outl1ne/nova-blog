<?php

namespace OptimistDigital\NovaBlog\Http;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\NovaBlog\Models\Page;
use OptimistDigital\NovaBlog\Models\Region;

class PostAndRegionController extends Controller
{
    public function getPostssAndRegions(NovaRequest $request)
    {
        $posts = Post::all();
        $regions = Region::all();

        return [
            'posts' => $posts,
            'regions' => $regions
        ];
    }
}
