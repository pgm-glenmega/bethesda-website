<?php
namespace modules\contactform;

use craft\web\UrlManager;
use yii\base\Event;
use yii\base\Module as BaseModule;

final class ContactFormModule extends BaseModule
{
    public $controllerNamespace = 'modules\contactform\controllers';

    public function init(): void
    {
        parent::init();

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            static function($event) {
                $event->rules['contactform/send-message'] = 'contactform/contact-form/send-message';
            }
        );
    }
}
