<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnneedableFieldsForUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login')->deafult('')->unique();
            $table->dropIndex('users_email_unique');
            $table->string('email')->nullable()->change();

            //need to change cartalyst.sentry.php login_attribute to email
        });

//        \DB::statement('INSERT INTO users(login) SELECT email FROM users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login');
            $table->string('email')->unique()->change();
        });
    }
}
