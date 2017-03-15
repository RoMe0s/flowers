<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('courier_id')->unsigned()->nullable();
            $table->foreign('courier_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('address_id')->unsigned()->nullable();
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade')->onUpdate('cascade');

            $table->float('delivery_price')->unsigned()->default(0);

            $table->integer('discount')->unsigned()->default(0);

            $table->integer('prepay')->unsigned();

            $table->string('recipient_name');

            $table->string('recipient_phone');

            $table->string('date')->nullable();

            $table->enum('time', array('1', '2', '3', '4', '5'))->nullable();

            $table->string('card_text')->nullable();

            $table->text('desc')->nullable();

            $table->text('result')->nullable();

            $table->enum('status', array('0', '1', '2', '3', '4', '5'));

            $table->timestamp('deleted_at')->nullable();

            $table->timestamps();
        });
        Schema::create('order_items', function(Blueprint $table) {
           $table->increments('id');

           $table->string('itemable_type');

           $table->integer('itemable_id')->unsigned();

           $table->integer('order_id')->unsigned();
           $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');

           $table->float('price');

           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_items');
        Schema::drop('orders');
    }
}
