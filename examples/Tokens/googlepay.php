<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Tokenize Google Pay wallet.
 */

/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Used namespaces.
 */
use Checkout\CheckoutApi;
use Checkout\Models\Tokens\GooglePay;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');
$checkout->configuration()->setPublicKey('public_key_goes_here'); // Public key necessary to generate tokens


/**
 * Create a Google Pay token
 */
$googlepay = new GooglePay('protocolVersion_goes_here', 'signature_goes_here', 'signedMessage_goes_here');
$token = $checkout->tokens()->request($googlepay); // Request token

var_dump($token);
