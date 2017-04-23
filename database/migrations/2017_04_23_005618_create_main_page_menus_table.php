<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainPageMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_page_menus', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('status')->default(true);

            $table->integer('position')->default(0);

            $table->integer('menuable_id')->unsigned();

            $table->string('menuable_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('main_page_menus');
    }
}
