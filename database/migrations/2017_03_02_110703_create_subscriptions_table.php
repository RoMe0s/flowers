<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->float('price');
            $table->string('image')->nullable();
            $table->integer('position')->unsigned()->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('subscription_translations', function (Blueprint $table) {
           $table->increments('id');
           $table->string('locale')->index();
           $table->integer('subscription_id')->unsigned();
           $table->foreign('subscription_id')->references('id')->on('subscriptions')->onUpdate('cascade')->onDelete('cascade');
           $table->string('title');
           $table->text('content')->nullable();
           $table->unique(['subscription_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subscription_translations');
        Schema::drop('subscriptions');
    }
}
