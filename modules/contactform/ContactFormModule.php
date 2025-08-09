<?php

namespace modules\contactform\controllers;

use Craft;
use craft\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\helpers\Html;

class ContactFormController extends Controller
{
    protected array|int|bool $allowAnonymous = true; // Allow public access

    public function actionSendMessage(): Response
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();

        // Get and sanitize form data
        $name = Html::encode($request->getBodyParam('name'));
        $email = Html::encode($request->getBodyParam('email'));
        $title = Html::encode($request->getBodyParam('title'));
        $message = Html::encode($request->getBodyParam('message'));

        // Validate required fields
        if (!$name || !$email || !$title || !$message) {
            return $this->asJson(['success' => false, 'message' => 'All fields are required.']);
        }

        // Admin recipient (where the message is sent)
        $adminEmail = 'glen.meganck@gmail.com';

        // Your SMTP sender email (must match your Gmail SMTP account)
        $smtpSenderEmail = 'glen.meganckl@gmail.com';

        // Email service
        $emailService = Craft::$app->getMailer()
            ->compose()
            ->setTo($adminEmail)
            ->setFrom([$smtpSenderEmail => "$name"]) // This makes the sender's name visible
            ->setReplyTo($email) // Reply goes to the sender
            ->setSubject($title)
            ->setTextBody("Name: $name\nEmail: $email\n\nMessage:\n$message");

        // Send the email
        if ($emailService->send()) {
            return $this->asJson(['success' => true, 'message' => 'Your message has been sent successfully.']);
        } else {
            return $this->asJson(['success' => false, 'message' => 'Failed to send message.']);
        }
    }
}
