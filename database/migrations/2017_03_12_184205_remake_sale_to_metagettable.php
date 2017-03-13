<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemakeSaleToMetagettable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('slug', 255);
        });
        Schema::create('sale_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('short_content')->nullable();
            $table->text('content')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['sale_id', 'locale']);
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sale_translations');
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
