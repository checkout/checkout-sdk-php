<?php

header('Content-type: text/plain');

require "checkout.php";



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
$checkout = new CheckoutApi('sk_test_1dde8193-51d5-457a-8af5-1930200cd8cb',
                            true,
                            'pk_test_3f148aa9-347a-450d-b940-0a8645b324e7');


/**
 * Add new Klarna source.
 */

/* Get source */
$product = new Product();
$product->name = 'hajsbdfhajsdfa';
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


print_r($source); die();

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




$source = new KlarnaSource('asfd', 'GB', 'en-GB', $address, 1, array($product));
$payment = new Payment($source, 'GBP');
$payment->amount = 999;

$res = $checkout->payments()->request($payment);



var_dump($details);
