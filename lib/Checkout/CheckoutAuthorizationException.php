<?php

namespace Checkout;

class CheckoutAuthorizationException extends CheckoutException
{

    /**
     * @param $authorizationType
     * @return CheckoutAuthorizationException
     */
    public static function invalidAuthorization($authorizationType)
    {
        return new CheckoutAuthorizationException("Operation requires " . $authorizationType . " authorization type");
    }

    /**
     * @return CheckoutAuthorizationException
     */
    public static function invalidSecretKey()
    {
        return self::invalidKey(AuthorizationType::$secretKey);
    }

    /**
     * @return CheckoutAuthorizationException
     */
    public static function invalidPublicKey()
    {
        return self::invalidKey(AuthorizationType::$publicKey);
    }

    /**
     * @return CheckoutAuthorizationException
     */
    public static function invalidAccessTokenResponse()
    {
        return new CheckoutAuthorizationException(
            "The authorization server returned an invalid access token response"
        );
    }

    /**
     * @param $keyType
     * @return CheckoutAuthorizationException
     */
    private static function invalidKey($keyType)
    {
        return new CheckoutAuthorizationException($keyType . "  is required for this operation");
    }
}
