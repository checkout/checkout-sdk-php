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
     * @param $keyType
     * @return CheckoutAuthorizationException
     */
    private static function invalidKey($keyType)
    {
        return new CheckoutAuthorizationException($keyType . "  is required for this operation");
    }
}
