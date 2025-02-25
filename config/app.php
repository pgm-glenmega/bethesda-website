<?php

use craft\helpers\App;
use modules\contactform\ContactFormModule;

return [
    'id' => App::env('CRAFT_APP_ID') ?: 'CraftCMS',

    'modules' => [
        'contact-form' => [
            'class' => ContactFormModule::class,
        ],
    ],
    'bootstrap' => ['contact-form'],
];
