<?php

namespace Checkout\Previous;

class CheckoutPreviousSdkBuilder
{

    public function staticKeys()
    {
        return new CheckoutStaticKeysPreviousSdkBuilder();
    }

}
