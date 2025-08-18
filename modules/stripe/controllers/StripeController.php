<?php
namespace modules\stripe\controllers;

use Craft;
use craft\web\Controller;
use craft\helpers\App;
use craft\helpers\UrlHelper;
use yii\web\Response;

class StripeController extends Controller
{
    protected array|bool|int $allowAnonymous = true;

    private function setStripe(): void
    {
        $isProd = Craft::$app->config->env === 'production'; 
        $key = $isProd ? App::env('STRIPE_SECRET_KEY_LIVE') : App::env('STRIPE_SECRET_KEY_TEST');

        if (!$key) {
            Craft::error('Missing Stripe secret key for environment', __METHOD__);
            throw new \RuntimeException('Stripe not configured');
        }

        \Stripe\Stripe::setApiKey($key);
        \Stripe\Stripe::setAppInfo('Bethesda Ministry', '1.0', null, 'craftcms');
    }

    private function successUrl(string $fallbackPath): string
    {
        $u = App::env('STRIPE_SUCCESS_URL');
        return $u && str_starts_with($u, 'http')
            ? $u
            : UrlHelper::siteUrl($u ?: $fallbackPath);
    }

    private function cancelUrl(string $fallbackPath): string
    {
        $u = App::env('STRIPE_CANCEL_URL');
        return $u && str_starts_with($u, 'http')
            ? $u
            : UrlHelper::siteUrl($u ?: $fallbackPath);
    }

    /** MEMBERSHIP */
    public function actionCreateCheckoutSession(): ?Response
    {
        $this->requirePostRequest();
        $this->setStripe();

        $request = Craft::$app->getRequest();
        $email   = $request->getBodyParam('email');
        $priceId = App::env('STRIPE_PRICE_MEMBERSHIP_YEARLY');

        $session = \Stripe\Checkout\Session::create([
            'mode' => 'subscription',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'customer_email' => $email ?: null,
            'success_url' => $this->successUrl('membership/success'),
            'cancel_url'  => $this->cancelUrl('membership'),
        ]);

        return $this->redirect($session->url, 303);
    }

    /** DONATION */
    public function actionCreateDonationCheckoutSession(): ?Response
    {
        $this->requirePostRequest();
        $this->setStripe();

        $r = Craft::$app->getRequest();
        $amount    = (int)$r->getBodyParam('amount');
        $frequency = (string)$r->getBodyParam('frequency', 'one-time');
        $fullName  = (string)$r->getBodyParam('fullName', '');
        $email     = (string)$r->getBodyParam('email', '');
        $fund      = (string)$r->getBodyParam('fund', '');
        $currency  = strtolower(App::env('STRIPE_CURRENCY') ?: 'eur');

        if ($amount <= 0) {
            Craft::$app->getSession()->setError('Please enter a valid amount.');
            return $this->redirect($this->cancelUrl('donate'));
        }

        $amountCents   = $amount * 100;
        $productSuffix = $fund ? " – {$fund}" : '';

        $params = [
            'success_url'    => $this->successUrl('donate/success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => $this->cancelUrl('donate'),
            'customer_email' => $email ?: null,
            'metadata' => [
                'type' => 'donation',
                'frequency' => $frequency,
                'fullName' => $fullName,
                'fund' => $fund,
            ],
        ];

        if ($frequency === 'monthly') {
            $params['mode'] = 'subscription';
            $params['line_items'] = [[
                'quantity' => 1,
                'price_data' => [
                    'currency'    => $currency,
                    'unit_amount' => $amountCents,
                    'recurring'   => ['interval' => 'month'],
                    'product_data'=> ['name' => 'Monthly Donation'.$productSuffix],
                ],
            ]];
        } else {
            $params['mode'] = 'payment';
            $params['payment_method_types'] = ['card','bancontact']; // BE one-time
            $params['line_items'] = [[
                'quantity' => 1,
                'price_data' => [
                    'currency'    => $currency,
                    'unit_amount' => $amountCents,
                    'product_data'=> ['name' => 'Donation'.$productSuffix],
                ],
            ]];
        }

        try {
            $session = \Stripe\Checkout\Session::create($params);
        } catch (\Throwable $e) {
            Craft::error('Stripe Donation Checkout error: '.$e->getMessage(), __METHOD__);
            Craft::$app->getSession()->setError('Unable to start checkout.');
            return $this->redirect($this->cancelUrl('donate'));
        }

        return $this->redirect($session->url, 303);
    }

    public function actionWebhook(): Response
    {
        $this->requirePostRequest();
        $payload   = Craft::$app->getRequest()->getRawBody();
        $sigHeader = Craft::$app->getRequest()->getHeaders()->get('Stripe-Signature');

        $isProd = Craft::$app->config->env === 'production';
        $secret = $isProd ? App::env('STRIPE_WEBHOOK_SECRET_LIVE') : App::env('STRIPE_WEBHOOK_SECRET_TEST');

        try {
            $event = $secret
                ? \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret)
                : json_decode($payload, true);
        } catch (\Throwable $e) {
            Craft::error('Stripe webhook signature failed: '.$e->getMessage(), __METHOD__);
            return $this->asJson(['received' => false])->setStatusCode(400);
        }

        return $this->asJson(['received' => true]);
    }
}
