<?php

namespace Checkout;


use Checkout\Four\FourStaticKeysCheckoutSdkBuilder;

class CheckoutFourSdk
{

    public static function staticKeys(): FourStaticKeysCheckoutSdkBuilder
    {
        return new FourStaticKeysCheckoutSdkBuilder();
    }

    public static function oAuth(): FourOAuthCheckoutSdkBuilder
    {
        return new FourOAuthCheckoutSdkBuilder();
    }

}


