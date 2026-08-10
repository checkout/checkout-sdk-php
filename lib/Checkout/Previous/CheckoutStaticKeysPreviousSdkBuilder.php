<?php

namespace Checkout\Previous;

use Checkout\AbstractStaticKeysCheckoutSdkBuilder;
use Checkout\ApiClient;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\SdkCredentialsInterface;

class CheckoutStaticKeysPreviousSdkBuilder extends AbstractStaticKeysCheckoutSdkBuilder
{

    const PUBLIC_KEY_PATTERN = "/^pk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";
    const SECRET_KEY_PATTERN = "/^sk_(test_)?(\\w{8})-(\\w{4})-(\\w{4})-(\\w{4})-(\\w{12})$/";

    /**
     * The Previous (ABC) platform predates merchant-specific subdomains, so it is exempt from
     * the mandatory environmentSubdomain/useLegacyDomain configuration.
     *
     * @return bool
     */
    protected function requiresEnvironmentSubdomain()
    {
        return false;
    }

    /**
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
        return new PreviousStaticKeysSdkCredentials($this->secretKey, $this->publicKey);
    }

    /**
     * @return CheckoutApi
     * @throws CheckoutArgumentException|CheckoutArgumentException
     */
    public function build()
    {
        $this->validatePublicKey($this->publicKey, self::PUBLIC_KEY_PATTERN);
        $this->validateSecretKey($this->secretKey, self::SECRET_KEY_PATTERN);
        $this->validateEnvironmentSettings();
        $configuration = new CheckoutConfiguration(
            $this->getSdkCredentials(),
            $this->environment,
            $this->httpClientBuilder,
            $this->logger
        );
        $environmentSubdomain = $this->getEnvironmentSubdomain();
        if ($environmentSubdomain !== null) {
            $configuration->setEnvironmentSubdomain($environmentSubdomain);
        }
        return new CheckoutApi($configuration);
    }
}
