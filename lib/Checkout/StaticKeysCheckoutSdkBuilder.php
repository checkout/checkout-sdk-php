<?php

namespace Checkout;

class StaticKeysCheckoutSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{

    private const PUBLIC_KEY_PATTERN = "/^pk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";
    private const SECRET_KEY_PATTERN = "/^sk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";

    public function setPublicKey(string $publicKey): void
    {
        $this->validatePublicKey($publicKey, self::PUBLIC_KEY_PATTERN);
        $this->publicKey = $publicKey;
    }

    public function setSecretKey(string $secretKey): void
    {
        $this->validateSecretKey($secretKey, self::SECRET_KEY_PATTERN);
        $this->secretKey = $secretKey;
    }

    /**
     * @return SdkCredentialsInterface
     */
    protected function getSdkCredentials(): SdkCredentialsInterface
    {
        return new StaticKeysSdkCredentials($this->secretKey, $this->publicKey);
    }

    /**
     * @return CheckoutApi
     */
    public function build(): CheckoutApi
    {
        $configuration = new CheckoutConfiguration($this->getSdkCredentials(), $this->environment, $this->httpClientBuilder, $this->logger);
        $apiClient = new ApiClient($configuration);
        return new CheckoutApi($apiClient, $configuration);
    }
}
