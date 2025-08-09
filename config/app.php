<?php

use craft\helpers\App;
use modules\contactform\ContactFormModule;
use modules\stripe\StripeModule;


return [
    'id' => App::env('CRAFT_APP_ID') ?: 'CraftCMS',

    'modules' => [
        'contact-form' => [
            'class' => ContactFormModule::class,
        ],
        'stripe' => [
            'class' => StripeModule::class,
        ]
    ],
    'bootstrap' => ['contact-form', 'stripe'],
];
