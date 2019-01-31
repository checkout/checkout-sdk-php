<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Tokenize Card Pay wallet.
 */

/**
 * Include SDK
 */
require_once "../../checkout.php";


/**
 * Used namespaces.
 */
use Checkout\CheckoutApi;
use Checkout\Models\Tokens\Card;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_goes_here');
$checkout->configuration()->setPublicKey('public_key_goes_here'); // Public key necessary to generate tokens


/**
* Create a Card token
*/
$card = new Card('4242424242424242', 12, 2020);
$token = $checkout->tokens()->request($card);

var_dump($token);
