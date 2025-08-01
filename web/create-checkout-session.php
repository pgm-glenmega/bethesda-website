<?php
require __DIR__ . '/../vendor/autoload.php'; // Adjust if needed

\Stripe\Stripe::setApiKey('sk_test_51Rqfn0362luTOlRBcO0HAsv7lrS1AT4PetZXr1EyTRBXbGIpH0R0ZsOvaQUsMwWqDdRACCygbFRtvQG3zAuYn9d400wGHAbbEu');

$domain = 'https://bethesda.ddev.site/'; // Change to your dev URL (or live domain later)

$checkout_session = \Stripe\Checkout\Session::create([
  'mode' => 'subscription',
  'line_items' => [[
    'price' => 'price_1RqrCm362luTOlRBXjOTOEfo',
    'quantity' => 1,
  ]],
  'customer_email' => $_POST['email'],
  'success_url' => $domain . '/membership-success',
  'cancel_url' => $domain . '/membership',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
exit;
