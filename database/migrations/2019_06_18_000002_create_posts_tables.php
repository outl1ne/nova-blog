<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTables extends Migration
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


        // Rename posts table
        Schema::rename($tableName, $postsTableName);

        // Drop deprecated type column
        Schema::table($postsTableName, function ($table) {
            $table->dropColumn('type');
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

        // Not worth the effort to undo the massive amount of changes in "up"
        // as there's no usecase to undoing just this migration

        Schema::dropIfExists($postsTableName);
    }
}
