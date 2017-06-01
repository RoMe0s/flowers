<?php

use App\Models\UserInfo;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Database\Migrations\Migration;

class PopulateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*Sentry::register(
            [
                'login'     => '70001112233',
                'phone'     => '70001112233',
                'email'     => 'admin@admin.com',
                'password'  => 'admin',
                'activated' => 1,
            ]
        );

        // Assign user permissions
        $adminGroup = Sentry::getGroupProvider()->findByName('Administrators');

        $adminUser = Sentry::getUserProvider()->findByLogin('70001112233');

        $adminUser->activated = true;
        $adminUser->save();

        $user_info = new UserInfo([
            'name' => 'admin',
        ]);
        $adminUser->info()->save($user_info);

        $adminUser->addGroup($adminGroup);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
