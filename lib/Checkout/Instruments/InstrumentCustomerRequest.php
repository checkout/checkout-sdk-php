<?php

namespace Checkout\Instruments;

use Checkout\Common\CustomerRequest;
use Checkout\Common\Phone;

class InstrumentCustomerRequest extends CustomerRequest
{

    public bool $default;

    public Phone $phone;

}
