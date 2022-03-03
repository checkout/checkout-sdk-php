<?php

namespace Checkout\Instruments\Four\Create;

use Checkout\Common\Phone;

class CreateCustomerInstrumentRequest
{
    public string $id;

    public string $email;

    public string $name;

    public Phone $phone;

    public bool $default;
}
