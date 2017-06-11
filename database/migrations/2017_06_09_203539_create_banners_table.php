<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function(Blueprint $table) {
            $table->increments('id'); 
            $table->string('layout_position');
            $table->boolean('status')->default(true);
            $table->string('template');
        });
        Schema::create('banner_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('banner_id')->unsigned();
            $table->string('image');
            $table->string('bullet')->nullable();
            $table->boolean('status')->default(true);
            $table->integer('position')->unsigned()->default(0);
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::create('banner_item_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('content')->nullable();
            $table->string('locale')->index();
            $table->integer('banner_item_id')->unsigned()->index();

            $table->unique(['banner_item_id', 'locale']);

            $table->foreign('banner_item_id')->references('id')->on('banner_items')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('banner_item_translations');
        Schema::drop('banner_items');
        Schema::drop('banners');
    }
}
