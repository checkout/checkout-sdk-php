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
use Checkout\Models\Address;
use Checkout\Models\Product;
use Checkout\Models\Sources\Klarna;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\KlarnaSource;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here'); // Klarna enabled sandbox account: sk_test_1dde8193-51d5-457a-8af5-1930200cd8cb


/**
 * Add new Klarna source.
 */

/* Get source */
$product = new Product();
$product->name = '';
$product->quantity = 1;
$product->unit_price = 999;
$product->tax_rate = 1;
$product->total_amount = 999;
$product->total_tax_amount = 1;

$klarna = new Klarna('GB', 'GBP', 'en-GB', 999, 1, array($product));
$source = $checkout->sources()->add($klarna);


/*
 * [Authorize Klarna]:  Pass $source->getTokenId() to Klarna JS SDK.
 *                      Learn more at https://docs.checkout.com/docs/klarna.
 */


/* Billing/Shipping */
$address = new Address();
$address->given_name = '';
$address->family_name = '';
$address->email = '';
$address->title = '';
$address->street_address = '';
$address->street_address2 = '';
$address->postal_code = '';
$address->city = '';
$add->region = '';
$address->phone = '';
$address->country = '';



/* `authorization_token` from Klarna JS SDK*/
$method = new KlarnaSource($klarnaAuthToken, 'GB', 'en-GB', $address, 1, array($product));
$payment = new Payment($method, 'GBP');
$payment->amount = 999;

$res = $checkout->payments()->request($payment);



var_dump($details);

