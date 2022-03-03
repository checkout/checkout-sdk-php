<?php

namespace Checkout\Four;

use Checkout\AbstractStaticKeysCheckoutSdkBuilder;
use Checkout\ApiClient;
use Checkout\CheckoutConfiguration;
use Checkout\SdkCredentialsInterface;

class FourStaticKeysCheckoutSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{
    private const PUBLIC_KEY_PATTERN = "/^pk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";
    private const SECRET_KEY_PATTERN = "/^sk_(sbox_)?[a-z2-7]{26}[a-z2-7*#$=]$/";

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
        return new FourStaticKeysSdkCredentials($this->secretKey, $this->publicKey);
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
