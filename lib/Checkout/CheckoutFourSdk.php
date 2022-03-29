<?php

namespace Checkout;

use Checkout\Four\FourOAuthCheckoutSdkBuilder;
use Checkout\Four\FourStaticKeysCheckoutSdkBuilder;

class CheckoutFourSdk
{

    public static function staticKeys()
    {
        return new FourStaticKeysCheckoutSdkBuilder();
    }

    public static function oAuth()
    {
        return new FourOAuthCheckoutSdkBuilder();
    }
}
