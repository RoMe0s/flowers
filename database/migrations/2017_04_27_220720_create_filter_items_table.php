<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilterItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value');
            $table->boolean('status')->default(TRUE);
            $table->string('slug');
            $table->enum('type', ['<', '>']);
            $table->integer('position')->unsigned()->default(0); 
        });
        Schema::create('filter_item_translations', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('filter_item_id')->unsigned()->index();
            $table->string('locale')->index();
            $table->string('title');
            
            $table->unique(['locale', 'filter_item_id']);
            $table->foreign('filter_item_id')->references('id')->on('filter_items')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('filter_item_translations'); 
        Schema::drop('filter_items');
    }
}
