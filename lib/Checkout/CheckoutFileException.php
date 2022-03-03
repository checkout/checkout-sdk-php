<?php

namespace Checkout;

class CheckoutFileException extends CheckoutException
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}
