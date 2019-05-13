<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Capture a payment.
 */


/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Use namespaces.
 */
use Checkout\CheckoutApi;
use Checkout\Models\Payments\Capture;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');


/**
 * Capture.
 */
$details = $checkout->payments()->capture(new Capture('payment_id_goes_here'));

var_dump($details);
