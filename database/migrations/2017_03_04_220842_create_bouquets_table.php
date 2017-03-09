<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBouquetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
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
        Schema::create('bouquets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position')->unsigned()->default(0);
            $table->boolean('status')->default(true);
            $table->string('slug', 255);
            $table->float('price');
            $table->string('image')->nullable();
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('types')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('count')->unsigned();
            $table->timestamps();
        });
        Schema::create('bouquet_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bouquet_id')->unsigned();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->text('short_content')->nullable();
            $table->text('content')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();

            $table->unique(['bouquet_id', 'locale']);
            $table->foreign('bouquet_id')->references('id')->on('bouquets')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('type_translations');
        Schema::drop('types');
        Schema::drop('bouquet_translations');
        Schema::drop('bouquets');
    }
}
