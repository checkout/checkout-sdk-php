<?php

namespace Checkout;

class CheckoutFileException extends CheckoutException
{

    public function __construct($message)
    {
        parent::__construct($message);
    }

}
