<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flowers', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('status')->default(true);
            $table->integer('position')->default(0);
            $table->timestamps();
        });
        Schema::create('flower_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('flower_id')->unsigned();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['flower_id', 'locale']);
            $table->foreign('flower_id')->references('id')->on('flowers')->onDelete('cascade')->onUpdate('cascade');
        });
        Schema::create('flowers_colors', function(Blueprint $table) {
           $table->integer('flower_id')->unsigned();
           $table->integer('color_id')->unsigned();
           $table->index(['flower_id', 'color_id']);
           $table->foreign('flower_id')->references('id')->on('flowers')->onDelete('cascade')->onUpdate('cascade');
           $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('flowers_color');
        Schema::drop('flower_translations');
        Schema::drop('flowers');
    }
}
