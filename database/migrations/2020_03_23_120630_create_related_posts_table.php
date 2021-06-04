<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $postsTable = config('nova-blog.blog_posts_table', 'nova_blog_posts');
        $relatedPostsTable = config('nova-blog.blog_related_posts_table', 'nova_blog_related_posts');

        Schema::create($relatedPostsTable, function (Blueprint $table) use ($postsTable) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->bigInteger('post_id')->unsigned()->nullable();
            $table->foreign('post_id')->references('id')->on($postsTable);
            $table->bigInteger('related_post_id')->unsigned()->nullable();
            $table->foreign('related_post_id')->references('id')->on($postsTable);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $relatedPostsTable = config('nova-blog.blog_related_posts_table', 'nova_blog_related_posts');
        Schema::dropIfExists($relatedPostsTable);
    }
}
