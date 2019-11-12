<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use OptimistDigital\NovaBlog\NovaBlog;

class AddDraftsToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $postsTableName = NovaBlog::getPostsTableName();

        Schema::table($postsTableName, function ($table) use ($postsTableName) {
            $table->string('preview_token')->nullable();
            $table->boolean('published')->default(true);
            $table->bigInteger('draft_parent_id')->nullable()->unsigned();
            $table->foreign('draft_parent_id')->references('id')->on($postsTableName)->onDelete('cascade');
            $table->dropUnique("{$postsTableName}_slug_unique");
            $table->unique(['slug', 'published'], "{$postsTableName}_locale_slug_published_unique");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $postsTableName = NovaBlog::getPagesTableName();

        Schema::table($postsTableName, function ($table) use ($postsTableName) {
            $table->dropForeign($postsTableName.'_draft_parent_id_foreign');
            $table->dropColumn('draft_parent_id');
            $table->dropColumn('published');
            $table->dropColumn('preview_token');
            $table->dropUnique("{$postsTableName}_locale_slug_published_unique");
            $table->unique(['slug'], "{$postsTableName}_slug_unique");
        });
    }
}
