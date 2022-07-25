<?php

namespace Checkout;

abstract class AbstractStaticKeysCheckoutSdkBuilder extends AbstractCheckoutSdkBuilder
{

    protected $publicKey = null;
    protected $secretKey = null;

    /**
     * @param string $key
     * @param string $secretKeyPattern
     * @throws CheckoutArgumentException
     */
    protected function validateSecretKey($key, $secretKeyPattern)
    {
        if ($this->validKey($secretKeyPattern, $key)) {
            return;
        }
        throw new CheckoutArgumentException("invalid secret key");
    }

    /**
     * @param string $key
     * @param string $publicKeyPattern
     * @throws CheckoutArgumentException
     */
    protected function validatePublicKey($key, $publicKeyPattern)
    {
        if (empty($key)) {
            return;
        }
        if ($this->validKey($publicKeyPattern, $key)) {
            return;
        }
        throw new CheckoutArgumentException("invalid public key");
    }

    private function validKey($pattern, $key)
    {
        return preg_match($pattern, $key);
    }

    abstract protected function publicKey($publicKey);

    abstract protected function secretKey($secretKey);
}
