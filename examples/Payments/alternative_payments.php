<?php

/**
 * Checkout.com 2010 - 2018.
 * Authorised and regulated as an electronic money institution by the UK Financial Conduct Authority (FCA) under number 900816.
 *
 * Example: Get payment actions.
 */


/**
 * Include SDK
 */
require_once "../../checkout.php";

/**
 * Use namespaces.
 */

use Checkout\CheckoutApi;
use Checkout\Models\Payments\AlipaySource;
use Checkout\Models\Payments\BoletoSource;
use Checkout\Models\Payments\GiropaySource;
use Checkout\Models\Payments\IdealSource;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Payments\PoliSource;
use Checkout\Models\Payments\SofortSource;

/**
 * Create new instance of Checkout
 */
$checkout = new CheckoutApi('secret_key_id_goes_here');



// Giropay
$giropay = new Payment(new GiropaySource('purpose', 'bic'), 'EUR');
$giropay->capture = false;
$giropay->amount = 999;
$payment = $checkout->payments()->request($giropay);



// iDEAL
$ideal = new Payment(new IdealSource('issuer_id'), 'EUR');
$ideal->capture = false;
$ideal->amount = 999;
$payment = $checkout->payments()->request($ideal);



// Boleto
$boleto = new Payment(new BoletoSource('customer_name', 'year-month-day', 'cpf'), 'BRL');
$boleto->amount = 999;
$payment = $checkout->payments()->request($boleto);



// Poli
$poli = new Payment(new PoliSource(), 'AUD');
$poli->amount = 999;
$payment = $checkout->payments()->request($poli);



// Alipay
$alipay = new Payment(new AlipaySource(), 'USD');
$alipay->amount = 999;
$payment = $checkout->payments()->request($alipay);



// Sofort
$sofort = new Payment(new SofortSource(), 'EUR');
$sofort->amount = 999;
$payment = $checkout->payments()->request($sofort);
