<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturedImageToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $postsTable = config('nova-blog.blog_posts_table', 'nova_blog_posts');

        Schema::table($postsTable, function (Blueprint $table) {
            $table->string('featured_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $postsTable = config('nova-blog.blog_posts_table', 'nova_blog_posts');

        Schema::table($postsTable, function (Blueprint $table) {
            $table->dropColumn('featured_image');
        });
    }
}
