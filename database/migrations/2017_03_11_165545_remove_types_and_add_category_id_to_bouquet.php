<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveTypesAndAddCategoryIdToBouquet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bouquets', function (Blueprint $table) {
            $table->dropForeign('bouquets_type_id_foreign');
            $table->dropColumn('type_id');
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });
        Schema::drop('type_translations');
        Schema::drop('types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->increments('id');
        });
        Schema::create('type_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['type_id', 'locale']);
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::table('bouquets', function (Blueprint $table) {
            $table->dropForeign('bouquets_category_id_foreign');
            $table->dropColumn('category_id');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('types')->onUpdate('cascade')->onDelete('cascade');
        });
    }
}
