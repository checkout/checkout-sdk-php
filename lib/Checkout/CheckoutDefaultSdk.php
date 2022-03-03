<?php

namespace Checkout;

class CheckoutDefaultSdk
{

    public static function staticKeys(): StaticKeysCheckoutSdkBuilder
    {
        return new StaticKeysCheckoutSdkBuilder();
    }

}


