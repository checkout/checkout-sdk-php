<?php

namespace Checkout;

use Checkout\Four\AbstractStaticKeysSdkCredentials;

class StaticKeysSdkCredentials extends AbstractStaticKeysSdkCredentials
{

    /**
     * @param string|null $secretKey
     * @param string|null $publicKey
     */
    public function __construct(?string $secretKey, ?string $publicKey)
    {
        parent::__construct($secretKey, $publicKey);
    }

    /**
     * @throws CheckoutAuthorizationException
     */
    function getAuthorization(string $authorizationType): SdkAuthorization
    {
        switch ($authorizationType) {
            case AuthorizationType::$publicKey:
                if (empty($this->publicKey)) {
                    throw CheckoutAuthorizationException::invalidPublicKey();
                }
                return new SdkAuthorization(PlatformType::$default, $this->publicKey);
            case AuthorizationType::$secretKey:
                if (empty($this->secretKey)) {
                    throw CheckoutAuthorizationException::invalidSecretKey();
                }
                return new SdkAuthorization(PlatformType::$default, $this->secretKey);
            default:
                throw CheckoutAuthorizationException::invalidAuthorization($authorizationType);
        }
    }
}
