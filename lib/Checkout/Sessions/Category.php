<?php

namespace Checkout\Sessions;

/**
 * Indicates the category of the authentication request.
 *
 * Used by SessionRequest::$authentication_category.
 *
 * [Optional]
 * Default: $payment
 */
final class Category
{
    /**
     * The authentication is for a payment. This is the default.
     * @var string
     */
    public static $payment = "payment";

    /**
     * The authentication is not for a payment. This is the value applied when the session is created
     * without an amount.
     * @var string
     */
    public static $non_payment = "non_payment";
}
