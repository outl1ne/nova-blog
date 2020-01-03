<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use OptimistDigital\NovaBlog\NovaBlog;

class AddDraftsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $postsTable = config('nova-blog.blog_posts_table', 'nova_blog_posts');

        Schema::table($postsTable, function ($table) use ($postsTable) {
            $table->string('preview_token')->nullable();
            $table->boolean('published')->default(true);
            $table->bigInteger('draft_parent_id')->nullable()->unsigned();
            $table->foreign('draft_parent_id')->references('id')->on($postsTable)->onDelete('cascade');
            $table->dropUnique("{$postsTable}_slug_unique");
            $table->unique(['slug', 'published'], "{$postsTable}_locale_slug_published_unique");
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

        Schema::table($postsTable, function ($table) use ($postsTable) {
            $table->dropForeign($postsTable.'_draft_parent_id_foreign');
            $table->dropColumn('draft_parent_id');
            $table->dropColumn('published');
            $table->dropColumn('preview_token');
            $table->dropUnique("{$postsTable}_locale_slug_published_unique");
            $table->unique(['slug'], "{$postsTable}_slug_unique");
        });
    }
}
