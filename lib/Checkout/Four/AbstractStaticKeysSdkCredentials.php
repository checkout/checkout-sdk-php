<?php

namespace Checkout\Four;

use Checkout\SdkCredentialsInterface;

abstract class AbstractStaticKeysSdkCredentials implements SdkCredentialsInterface
{
    protected ?string $publicKey;
    protected ?string $secretKey;

    /**
     * @param string|null $secretKey
     * @param string|null $publicKey
     */
    public function __construct(?string $secretKey, ?string $publicKey)
    {
        $this->publicKey = $publicKey;
        $this->secretKey = $secretKey;
    }

}
