<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Authorize a payment/Verify card.
 */


/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Use namespaces.
 */
use Checkout\CheckoutApi;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\TokenSource;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');


/**
 * Create new payment method.
 */
$method = new TokenSource('token_id_goes_here');
$payment = new Payment($method, 'USD');
$payment->capture = false;
$details = $checkout->payments()->request($payment);

var_dump($details);
