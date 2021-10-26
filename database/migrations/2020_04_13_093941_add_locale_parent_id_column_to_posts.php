<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocaleParentIdColumnToPosts extends Migration
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

            $table->unsignedBigInteger('locale_parent_id')->nullable();
            $table->foreign('locale_parent_id')->references('id')->on($postsTable);
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

            $table->dropForeign(['locale_parent_id']);
            $table->dropColumn('locale_parent_id');
        });
    }
}
