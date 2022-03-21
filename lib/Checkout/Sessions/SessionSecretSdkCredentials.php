<?php

namespace Checkout\Sessions;

use Checkout\AuthorizationType;
use Checkout\CheckoutAuthorizationException;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;

final class SessionSecretSdkCredentials implements SdkCredentialsInterface
{
    public $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param $authorizationType
     * @return SdkAuthorization
     * @throws CheckoutAuthorizationException
     */
    public function getAuthorization($authorizationType)
    {
        if ($authorizationType == AuthorizationType::$custom) {
            return new SdkAuthorization(AuthorizationType::$custom, $this->secret);
        }
        throw CheckoutAuthorizationException::invalidAuthorization($authorizationType);
    }
}
