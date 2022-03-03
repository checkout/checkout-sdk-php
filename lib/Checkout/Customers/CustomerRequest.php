<?php

namespace Checkout\Customers;

use Checkout\Common\Phone;

class CustomerRequest
{
    public string $email;

    public string $name;

    public Phone $phone;

    public array $metadata;
}
