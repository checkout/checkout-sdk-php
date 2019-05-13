<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Tokenize Apple Pay wallet.
 */

/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Used namespaces.
 */
use Checkout\CheckoutApi;
use Checkout\Models\Tokens\ApplePay;
use Checkout\Models\Tokens\ApplePayHeader;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');
$checkout->configuration()->setPublicKey('public_key_goes_here'); // Public key necessary to generate tokens


/**
 * Create a Apple Pay token
 */
$header = new ApplePayHeader('transactionId_goes_here', 'publicKeyHash_goes_here', 'ephemeralPublicKey_goes_here');
$applepay = new ApplePay('version_goes_here', 'signature_goes_here', 'data_goes_here', $header);
$token = $checkout->tokens()->request($applepay); // Request token

var_dump($token);
