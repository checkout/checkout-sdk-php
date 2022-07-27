<?php

namespace Checkout;

class CheckoutSdk
{
    public static function builder()
    {
        return new CheckoutSdkBuilder();
    }
}
