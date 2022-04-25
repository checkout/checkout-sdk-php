<?php

namespace Checkout;

use Checkout\Four\FourOAuthCheckoutSdkBuilder;
use Checkout\Four\FourStaticKeysCheckoutSdkBuilder;

class CheckoutFourSdk
{

    /**
     * @return FourStaticKeysCheckoutSdkBuilder
     */
    public static function staticKeys()
    {
        return new FourStaticKeysCheckoutSdkBuilder();
    }

    /**
     * @return FourOAuthCheckoutSdkBuilder
     */
    public static function oAuth()
    {
        return new FourOAuthCheckoutSdkBuilder();
    }
}
