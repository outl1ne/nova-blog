<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugToCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categoriesTable = config('nova-blog.blog_categories_table', 'nova_blog_categories');
        Schema::table($categoriesTable, function (Blueprint $table) {
            $table->string('slug')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $categoriesTable = config('nova-blog.blog_categories_table', 'nova_blog_categories');
        Schema::table($categoriesTable, function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
