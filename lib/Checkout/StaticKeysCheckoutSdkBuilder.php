<?php

namespace Checkout;

class StaticKeysCheckoutSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{

    const PUBLIC_KEY_PATTERN = "/^pk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";
    const SECRET_KEY_PATTERN = "/^sk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";

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
        return new StaticKeysSdkCredentials($this->secretKey, $this->publicKey);
    }

    /**
     * @return CheckoutApi
     */
    public function build()
    {
        $configuration = new CheckoutConfiguration($this->getSdkCredentials(), $this->environment, $this->httpClientBuilder, $this->logger);
        $apiClient = new ApiClient($configuration);
        return new CheckoutApi($apiClient, $configuration);
    }
}
