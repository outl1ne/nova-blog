<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('nova-blog.table', 'nova_blog');

        Schema::create($table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->enum('type', ['post']);
            $table->string('title');
            $table->string('slug')->default('')->unique();
            $table->string('post_content');
            $table->timestamp('published_at');
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_image')->nullable();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = config('nova-blog.table', 'nova_blog');

        Schema::dropIfExists($table);
    }
}
