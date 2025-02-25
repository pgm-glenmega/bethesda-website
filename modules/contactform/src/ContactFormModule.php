<?php

namespace modules\contactform;

use Craft;
use yii\base\Module;
use yii\base\Event;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

class ContactFormModule extends Module
{
    public function init()
    {
        parent::init();

        // Define alias for autoloading
        Craft::setAlias('@modules/contactform', __DIR__);

        // Register the Controller
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['contact-form/send-message'] = 'contact-form/contact-form/send-message';
            }
        );
    }
}
