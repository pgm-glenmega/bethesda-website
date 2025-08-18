<?php

use modules\contactform\ContactFormModule;
use modules\stripe\StripeModule;

return [
    'modules' => [
        'contactform' => ContactFormModule::class,
        'stripe'      => StripeModule::class, // remove if you don't actually have it
    ],
    'bootstrap' => ['contactform', 'stripe'],
];
