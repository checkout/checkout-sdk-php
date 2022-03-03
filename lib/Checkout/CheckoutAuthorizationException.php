<?php

namespace Checkout;

class CheckoutAuthorizationException extends CheckoutException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    /**
     * @param string $authorizationType
     * @return CheckoutAuthorizationException
     */
    public static function invalidAuthorization(string $authorizationType): CheckoutAuthorizationException
    {
        return new CheckoutAuthorizationException("Operation requires " . $authorizationType . " authorization type");
    }

    /**
     * @return CheckoutAuthorizationException
     */
    public static function invalidSecretKey(): CheckoutAuthorizationException
    {
        return self::invalidKey(AuthorizationType::$secretKey);
    }

    /**
     * @return CheckoutAuthorizationException
     */
    public static function invalidPublicKey(): CheckoutAuthorizationException
    {
        return self::invalidKey(AuthorizationType::$publicKey);
    }

    /**
     * @param string $keyType
     * @return CheckoutAuthorizationException
     */
    private static function invalidKey(string $keyType): CheckoutAuthorizationException
    {
        return new CheckoutAuthorizationException($keyType . "  is required for this operation");
    }

}
