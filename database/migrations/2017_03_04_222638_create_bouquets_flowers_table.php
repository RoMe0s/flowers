<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBouquetsFlowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bouquets_flowers', function(Blueprint $table) {
            $table->integer('bouquet_id')->unsigned();
            $table->foreign('bouquet_id')->references('id')->on('bouquets')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('flower_id')->unsigned();
            $table->foreign('flower_id')->references('id')->on('flowers')->onUpdate('cascade')->onDelete('cascade');
            $table->index(['bouquet_id', 'flower_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bouquets_flowers');
    }
}
