<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class MakeSlugLocalePairUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('nova-blog.table', 'nova_blog');
        $postsTableName = $tableName . '_posts';

        Schema::table($postsTableName, function ($table) {
            $table->dropUnique('nova_blog_slug_unique');
            $table->unique(['locale', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableName = config('nova-blog.table', 'nova_blog');
        $postsTableName = $tableName . '_posts';

        Schema::table($postsTableName, function ($table) {
            $table->dropUnique(['locale', 'slug']);
            $table->unique('slug');
        });
    }
}
