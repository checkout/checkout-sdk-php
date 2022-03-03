<?php

namespace Checkout;

class CheckoutArgumentException extends CheckoutException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}
