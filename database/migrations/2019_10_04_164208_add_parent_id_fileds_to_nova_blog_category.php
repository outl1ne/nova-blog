<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Nova\Fields\Number;

class AddParentIdFiledsToNovaBlogCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableCategory = config('nova-blog.table_categories', 'nova_blog_categories');
        Schema::table($tableCategory, function (Blueprint $table) use ($tableCategory) {
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on($tableCategory);
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
            $table->dropForeign('parent_id');
            $table->dropColumn('parent_id');
        });
    }
}
