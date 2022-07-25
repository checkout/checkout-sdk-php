<?php

namespace Checkout;

use Checkout\Previous\CheckoutPreviousSdkBuilder;

class CheckoutSdkBuilder
{

    /**
     * @return CheckoutPreviousSdkBuilder
     */
    public function previous()
    {
        return new CheckoutPreviousSdkBuilder();
    }

    /**
     * @return CheckoutStaticKeysSdkBuilder
     */
    public function staticKeys()
    {
        return new CheckoutStaticKeysSdkBuilder();
    }

    /**
     * @return CheckoutOAuthSdkBuilder
     */
    public function oAuth()
    {
        return new CheckoutOAuthSdkBuilder();
    }
}
