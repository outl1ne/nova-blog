<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoFiledsToNovaBlogCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = config('nova-blog.table_categories', 'nova_blog_categories');
        Schema::table($table, function (Blueprint $table) {
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_image')->nullable();
            $table->string('category_introduction')->nullable();
            $table->string('category_content')->nullable();
            $table->integer('sort')->unsigned()->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableCategory = config('nova-blog.table_categories', 'nova_blog_categories');
        Schema::table($tableCategory, function (Blueprint $table) {
            $table->dropColumn('seo_title');
            $table->dropColumn('seo_description');
            $table->dropColumn('seo_image');
            $table->dropColumn('category_introduction');
            $table->dropColumn('category_content');
            $table->dropColumn('sort');
        });
    }
}
