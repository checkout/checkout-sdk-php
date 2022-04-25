<?php

namespace Checkout;

class StaticKeysCheckoutSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{

    const PUBLIC_KEY_PATTERN = "/^pk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";
    const SECRET_KEY_PATTERN = "/^sk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";

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
        $apiClient = new ApiClient($configuration);
        return new CheckoutApi($apiClient, $configuration);
    }
}
