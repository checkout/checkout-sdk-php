<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\ApplePay;

use Checkout\Common\EmptyAsObject;

/**
 * Additional version-dependent information used to decrypt and verify the Apple Pay payment (request
 * only).
 */
#[EmptyAsObject]
class ApplePayTokenDataHeader
{
    /**
     * The ephemeral_public_key value of the Apple Pay payment token header.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $ephemeral_public_key;

    /**
     * The public_key_hash value of the Apple Pay payment token header.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $public_key_hash;

    /**
     * The transaction_id value of the Apple Pay payment token header.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $transaction_id;
}
