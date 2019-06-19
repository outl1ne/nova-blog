<?php

namespace OptimistDigital\NovaBlog\Http;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\NovaBlog\Models\Post;


class PostController extends Controller
{
    public function getPosts(NovaRequest $request)
    {
        $posts = Post::all();


        return [
            'posts' => $posts

        ];
    }
}
