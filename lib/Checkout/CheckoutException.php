<?php

namespace Checkout;

use Exception;

class CheckoutException extends Exception
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
