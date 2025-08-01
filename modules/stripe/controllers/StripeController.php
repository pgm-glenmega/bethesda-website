<?php

namespace modules\stripe\controllers;

use Craft;
use yii\web\Response;
use craft\web\Controller;
use craft\helpers\UrlHelper;

class StripeController extends Controller
{
protected array|bool|int $allowAnonymous = true;

    public function actionCreateCheckoutSession(): ?response
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();
        $email = $request->getBodyParam('email');

        \Stripe\Stripe::setApiKey('sk_test_51Rqfn0362luTOlRBcO0HAsv7lrS1AT4PetZXr1EyTRBXbGIpH0R0ZsOvaQUsMwWqDdRACCygbFRtvQG3zAuYn9d400wGHAbbEu');

        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
                'price' => 'price_1RqrCm362luTOlRBXjOTOEfo', // Replace with your real price ID
                'quantity' => 1,
            ]],
            'customer_email' => $email,
            'success_url' => UrlHelper::siteUrl('membership/success'),
            'cancel_url' => UrlHelper::siteUrl('membership'),
        ]);

        return $this->redirect($checkoutSession->url);
    }
}
