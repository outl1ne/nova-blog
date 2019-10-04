<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisibleFiledsToNovaBlogCategory extends Migration
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
            $table->boolean('visible')->default(1);
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
            $table->dropColumn('visible');
            //
        });
    }
}
