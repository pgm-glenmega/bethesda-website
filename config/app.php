<?php

use modules\contactform\ContactFormModule;
use modules\stripe\StripeModule;

return [

    'aliases' => [
    '@modules' => dirname(__DIR__) . '/modules',
],

    'modules' => [
        'contactform' => ContactFormModule::class,
        'stripe'      => StripeModule::class,
    ],
    'bootstrap' => ['contactform', 'stripe'],
];
