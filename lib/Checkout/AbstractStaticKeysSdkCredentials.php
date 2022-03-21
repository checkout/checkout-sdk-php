<?php

namespace Checkout;

abstract class AbstractStaticKeysSdkCredentials implements SdkCredentialsInterface
{
    protected $publicKey;
    protected $secretKey;

    /**
     * @param string|null $secretKey
     * @param string|null $publicKey
     */
    public function __construct($secretKey, $publicKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

}
