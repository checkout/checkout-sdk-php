<?php

namespace Checkout;

class CheckoutApiException extends CheckoutException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}
