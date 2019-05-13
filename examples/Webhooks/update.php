<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Update a webhook.
 */

/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Used namespaces.
 */
use Checkout\CheckoutApi;
use Checkout\Models\Webhooks\Webhook;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');


/**
 * Update the webhook
 */
$webhook = new Webhook('https://example.com/inbound', 'webhook_id');
$details = $checkout->webhooks()->update($webhook);

var_dump($details);
