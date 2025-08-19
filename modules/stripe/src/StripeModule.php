<?php

namespace modules\stripe;

use Craft;
use yii\base\Module;

class StripeModule extends Module
{
    public function init()
    {
        parent::init();
        Craft::info('Stripe Module loaded', __METHOD__);
    }
}
