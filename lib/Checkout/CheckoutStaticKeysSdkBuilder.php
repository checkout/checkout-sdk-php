<?php

namespace Checkout;

class CheckoutStaticKeysSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{
    const PUBLIC_KEY_PATTERN = "/^pk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";
    const SECRET_KEY_PATTERN = "/^sk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";

    /**
     * /**
     * @param string $publicKey
     * @return $this
     */
    public function publicKey($publicKey)
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * @param string $secretKey
     * @return $this
     */
    public function secretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @return SdkCredentialsInterface
     */
    protected function getSdkCredentials()
    {
        return new StaticKeysSdkCredentials($this->secretKey, $this->publicKey);
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
