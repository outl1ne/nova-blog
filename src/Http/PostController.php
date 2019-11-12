<?php

namespace OptimistDigital\NovaBlog\Http;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\NovaBlog\Models\Post;

class PostController extends Controller
{
    public function publishPost($postId) {
        $postToPublish = Post::find($postId);

        if (isset($postToPublish)) {

            if (isset($postToPublish->draftParent)) {
                $publishedPost = $postToPublish->draftParent;
                $publishedPost->data = $postToPublish->data;
                $publishedPost->title = $postToPublish->title;
                $publishedPost->slug = $postToPublish->slug;
                $publishedPost->post_content = $postToPublish->post_content;
                $publishedPost->seo_title = $postToPublish->seo_title;
                $publishedPost->seo_description = $postToPublish->seo_description;
                $publishedPost->seo_image = $postToPublish->seo_image;
                $publishedPost->post_introduction = $postToPublish->post_introduction;
                $publishedPost->is_pinned = $postToPublish->is_pinned;
                $publishedPost->category_id = $postToPublish->category_id;
                $publishedPost->published = true;
                $publishedPost->save();
                $postToPublish->delete();
                return $publishedPost;
            } else {
                $postToPublish->published = true;
                $postToPublish->preview_token = null;
                $postToPublish->save();
                return $postToPublish;
            }
        }

        return $postToPublish;
    }
}
