<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Void a payment.
 */


/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Use namespaces.
 */
use Checkout\CheckoutApi;
use Checkout\Models\Payments\Voids;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');


/**
 * Void.
 */
$details = $checkout->payments()->void(new Voids('payment_id_goes_here'));

var_dump($details);
