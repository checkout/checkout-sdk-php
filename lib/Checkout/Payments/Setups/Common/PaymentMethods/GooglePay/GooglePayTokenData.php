<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\GooglePay;

/**
 * The Google Pay payment token data (request only).
 */
class GooglePayTokenData
{
    /**
     * The encryption and signing scheme used to create this message. If not set, the default is ECv0.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $protocol_version;

    /**
     * The signature that verifies the message came from Google, created using ECDSA.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $signature;

    /**
     * A serialized JSON string containing the encryptedMessage, ephemeralPublicKey, and tag. To simplify
     * the signature verification process, this value is serialized.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $signed_message;

    /**
     * The public key provided to the Google API in the tokenizationSpecification.parameters.gatewayMerchantId
     * field.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $tokenization_key;
}
