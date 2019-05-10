<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Create a SEPA source.
 */


/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Use namespaces.
 */

use Checkout\CheckoutApi;
use Checkout\Models\Sources\Sepa;
use Checkout\Models\Sources\SepaData;
use Checkout\Models\Sources\SepaAddress;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here'); // SEPA enabled sandbox account: sk_test_1dde8193-51d5-457a-8af5-1930200cd8cb


/**
 * Add new SEPA source.
 */


//$address = new BillingAddress('address_line_1', 'address_line_2', 'city', 'state', 'post_code', 'country_code'); @note: deprecated
$address = new SepaAddress('address_line_1', 'city', 'post_code', 'country_code');
$data = new SepaData('first_name', 'surname', 'iban', 'bic', 'descriptor', 'mandate');
$source = new Sepa($address, $data);
$details = $checkout->sources()->add($source);

var_dump($details);
