<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Delete a webhook.
 */

/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Used namespaces.
 */
use Checkout\CheckoutApi;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');


/**
 * Remove the webhook
 */
$details = $checkout->webhooks()->remove('webhook_id');

var_dump($details);
