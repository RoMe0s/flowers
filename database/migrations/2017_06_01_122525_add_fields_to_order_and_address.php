<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToOrderAndAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('specify')->default(false);
            $table->boolean('neighbourhood')->default(false);
            $table->boolean('accuracy')->default(false);
            $table->boolean('night')->default(false);
            $table->boolean('anonymously')->default(false);
            $table->string('address_string')->nullable();
            $table->integer('distance')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('specify');
            $table->dropColumn('neighbourhood');
            $table->dropColumn('accuracy');
            $table->dropColumn('night');
            $table->dropColumn('anonymously');
            $table->dropColumn('distance');
        });
    }
}
