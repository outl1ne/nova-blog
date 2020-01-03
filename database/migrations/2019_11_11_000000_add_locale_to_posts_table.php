<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocaleToPostsTable extends Migration
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
            $table->string('locale')->default('undefined');
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
            $table->dropColumn('locale');
        });
    }
}
