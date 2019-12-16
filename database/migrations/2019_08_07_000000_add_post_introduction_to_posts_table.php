<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostIntroductionToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('nova-blog.table', 'nova_blog_posts');

        Schema::table($table, function (Blueprint $table) {
            $table->string('post_introduction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = config('nova-blog.table', 'nova_blog_posts');

        Schema::table($table, function (Blueprint $table) {
            $table->dropColumn('post_introduction');
        });
    }
}
