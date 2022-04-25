<?php

namespace Checkout\Four;

use Checkout\AbstractStaticKeysCheckoutSdkBuilder;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\SdkCredentialsInterface;

class FourStaticKeysCheckoutSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{
    const PUBLIC_KEY_PATTERN = "/^pk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";
    const SECRET_KEY_PATTERN = "/^sk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";

    /**
     * @param string $publicKey
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @param string $secretKey
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return SdkCredentialsInterface
     */
    protected function getSdkCredentials()
    {
        return new FourStaticKeysSdkCredentials($this->secretKey, $this->publicKey);
    }

    /**
     * @return CheckoutApi
     * @throws CheckoutArgumentException
     */
    public function build()
    {
        $this->validatePublicKey($this->publicKey, self::PUBLIC_KEY_PATTERN);
        $this->validateSecretKey($this->secretKey, self::SECRET_KEY_PATTERN);
        $configuration = new CheckoutConfiguration($this->getSdkCredentials(), $this->environment, $this->httpClientBuilder, $this->logger);
        return new CheckoutApi($configuration);
    }
}
