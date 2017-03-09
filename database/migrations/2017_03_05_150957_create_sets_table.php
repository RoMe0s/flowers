<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->increments('id');

            $table->string('image')->nullable();
            $table->float('price');
            $table->integer('box_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('count')->unsigned();
            $table->boolean('status')->default(true);
            $table->integer('position')->unsigned()->default(0);
            $table->string('slug', 255);

            $table->foreign('box_id')->references('id')->on('boxes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('set_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('set_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('short_content')->nullable();
            $table->text('content')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['set_id', 'locale']);
            $table->foreign('set_id')->references('id')->on('sets')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::create('sets_flowers', function(Blueprint $table) {
           $table->integer('set_id')->unsigned();
           $table->integer('flower_id')->unsigned();
           $table->foreign('set_id')->references('id')->on('sets')->onDelete('cascade')->onUpdate('cascade');
           $table->foreign('flower_id')->references('id')->on('flowers')->onDelete('cascade')->onUpdate('cascade');
           $table->index(['set_id', 'flower_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sets_flowers');
        Schema::drop('set_translations');
        Schema::drop('sets');
    }
}
