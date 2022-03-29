<?php

namespace Checkout;

class CheckoutDefaultSdk
{

    public static function staticKeys()
    {
        return new StaticKeysCheckoutSdkBuilder();
    }

}
