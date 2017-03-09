<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_codes', function(Blueprint $table) {
           $table->integer('user_id')->unsigned();
           $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
           $table->integer('code_id')->unsigned();
           $table->foreign('code_id')->references('id')->on('codes')->onUpdate('cascade')->onDelete('cascade');
           $table->index(['user_id', 'code_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_codes');
    }
}
