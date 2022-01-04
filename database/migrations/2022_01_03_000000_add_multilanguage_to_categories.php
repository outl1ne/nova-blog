<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMultilanguageToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categoriesTable = config('nova-blog.blog_categories_table', 'nova_blog_categories');
        $locales = \OptimistDigital\NovaBlog\NovaBlog::getLocales();
        $mainLocale = reset($locales);

        DB::statement("UPDATE ".$categoriesTable." SET title = CONCAT('{\"".$mainLocale."\":\"',title,'\"}'),  slug = CONCAT('{\"".$mainLocale."\":\"',slug,'\"}') ");

        Schema::table($categoriesTable, function (Blueprint $table) {
            $table->json('title')->change();
            $table->json('slug')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
