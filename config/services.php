<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\Models\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => '1833017613587125',
        'client_secret' => '4f0819f30657bab4cd08d05af1ca4b3a',
        'redirect' => env('APP_URL').'/login/facebook/callback'
    ],

    'vkontakte' => [
        'client_id' => '5615179',
        'client_secret' => 'AnBxF3OzSfL3jfLbPfkJ',
        'redirect' => env('APP_URL').'/login/vkontakte/callback',
    ],

    'instagram' => [
        'client_id' => '218914fa10bb471380f32be95c1e85f5',
        'client_secret' => 'fd0f1bf27b214c129c664ad08c02e419',
        'redirect' => env('APP_URL').'/login/instagram/callback',
    ],

    'google' => [
        'client_id' => '401753573301-g2l9dr1p31qa8u6p6ruuqftmuvnfdinl.apps.googleusercontent.com',
        'client_secret' => 'pFGxOHfmme3rm_7TGytswR27',
        'redirect' => env('APP_URL').'/login/google/callback',
    ],

];
