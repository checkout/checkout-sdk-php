<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\ApplePay;

/**
 * The Apple Pay payment token data (request only).
 */
class ApplePayTokenData
{
    /**
     * The version of the payment token. The token uses EC_v1 for ECC-encrypted data, and RSA_v1 for
     * RSA-encrypted data.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $version;

    /**
     * The encrypted payment data, Base64-encoded.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $data;

    /**
     * The signature of the payment and header data. The signature includes the signing certificate, its
     * intermediate CA certificate, and information about the signing algorithm.
     * [Optional]
     * writeOnly
     * @var string
     */
    public $signature;

    /**
     * Additional version-dependent information used to decrypt and verify the payment.
     * [Optional]
     * writeOnly
     * @var ApplePayTokenDataHeader
     */
    public $header;
}
