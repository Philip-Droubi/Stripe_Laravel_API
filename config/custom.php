<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe Keys
    |--------------------------------------------------------------------------
    |
    */

    'stripe_secret' => env('STRIPE_SECRET_TEST_KEY'),
    'stripe_publishable' => env('STRIPE_PUBLISHABLE_TEST_KEY'),
];
