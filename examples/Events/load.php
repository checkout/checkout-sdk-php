<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Load a event.
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
 * Load a specific event.
 */
$event = $checkout->events()->load('event_id_goes_here');

var_dump($event);
