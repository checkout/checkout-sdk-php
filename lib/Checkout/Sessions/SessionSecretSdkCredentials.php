<?php

namespace Checkout\Sessions;

use Checkout\AuthorizationType;
use Checkout\CheckoutAuthorizationException;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;

final class SessionSecretSdkCredentials implements SdkCredentialsInterface
{
    public string $secret;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param string $authorizationType
     * @return SdkAuthorization
     * @throws CheckoutAuthorizationException
     */
    public function getAuthorization(string $authorizationType): SdkAuthorization
    {
        if ($authorizationType == AuthorizationType::$custom) {
            return new SdkAuthorization(AuthorizationType::$custom, $this->secret);
        }
        throw CheckoutAuthorizationException::invalidAuthorization($authorizationType);
    }
}
