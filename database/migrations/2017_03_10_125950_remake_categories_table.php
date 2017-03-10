<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemakeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 255);
            $table->integer('position')->unsigned()->default(0);
            $table->boolean('status')->default(true);
        });

        Schema::table('category_translations', function (Blueprint $table) {
             $table->renameColumn('title', 'name');
             $table->text('short_content')->nullable();
             $table->text('content')->nullable();
             $table->string('meta_title')->nullable();
             $table->string('meta_keywords')->nullable();
             $table->text('meta_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('position');
            $table->dropColumn('status');
        });
        Schema::table('category_translations', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->dropColumn('short_content');
            $table->dropColumn('content');
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('meta_description');
        });
    }
}
