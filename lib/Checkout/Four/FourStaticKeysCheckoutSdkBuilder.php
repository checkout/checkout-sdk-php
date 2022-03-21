<?php

namespace Checkout\Four;

use Checkout\AbstractStaticKeysCheckoutSdkBuilder;
use Checkout\CheckoutConfiguration;
use Checkout\SdkCredentialsInterface;

class FourStaticKeysCheckoutSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{
    const PUBLIC_KEY_PATTERN = "/^pk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";
    const SECRET_KEY_PATTERN = "/^sk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";

    public function setPublicKey($publicKey)
    {
        $this->validatePublicKey($publicKey, self::PUBLIC_KEY_PATTERN);
        $this->publicKey = $publicKey;
    }

    public function setSecretKey($secretKey)
    {
        $this->validateSecretKey($secretKey, self::SECRET_KEY_PATTERN);
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
     */
    public function build()
    {
        $configuration = new CheckoutConfiguration($this->getSdkCredentials(), $this->environment, $this->httpClientBuilder, $this->logger);
        return new CheckoutApi($configuration);
    }
}
