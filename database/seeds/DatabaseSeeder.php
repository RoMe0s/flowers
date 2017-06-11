<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        \Sentry::createUser([
            'login' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'admin'
        ]);

        Model::reguard();
    }
}
