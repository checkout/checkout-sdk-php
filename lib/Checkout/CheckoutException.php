<?php

namespace Checkout;

use Exception;

class CheckoutException extends Exception
{

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

}
