<?php

use modules\contactform\ContactFormModule;
use modules\stripe\StripeModule;

return [

    'aliases' => [
    '@modules' => dirname(__DIR__) . '/modules',
],

    'modules' => [
        // use NO hyphen so it matches your route target: contactform/...
        'contactform' => ContactFormModule::class,
        'stripe'      => StripeModule::class,
    ],
    'bootstrap' => ['contactform', 'stripe'],
];
