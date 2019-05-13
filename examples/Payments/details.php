<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Get payment details.
 */


/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Use namespaces.
 */
use Checkout\CheckoutApi;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');


/**
 * Get details.
 */
$details = $checkout->payments()->details('payment_id_goes_here');

var_dump($details);
