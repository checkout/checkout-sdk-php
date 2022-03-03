<?php

namespace Checkout;

abstract class AbstractStaticKeysCheckoutSdkBuilder extends AbstractCheckoutSdkBuilder
{

    protected ?string $publicKey = null;
    protected ?string $secretKey = null;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws CheckoutArgumentException
     */
    protected function validateSecretKey(string $key, string $secretKeyPattern): void
    {
        if ($this->validKey($secretKeyPattern, $key)) {
            return;
        }
        throw new CheckoutArgumentException("invalid secret key");
    }

    /**
     * @throws CheckoutArgumentException
     */
    protected function validatePublicKey(string $key, string $publicKeyPattern): void
    {
        if (empty($key)) {
            return;
        }
        if ($this->validKey($publicKeyPattern, $key)) {
            return;
        }
        throw new CheckoutArgumentException("invalid public key");
    }

    private function validKey(string $pattern, string $key): bool
    {
        return preg_match($pattern, $key);
    }

    protected abstract function getSdkCredentials(): SdkCredentialsInterface;

    protected abstract function setPublicKey(string $publicKey): void;

    protected abstract function setSecretKey(string $secretKey): void;

}
