<?php

namespace Checkout\Sessions\Source;

use Checkout\Common\Phone;
use Checkout\Sessions\SessionAddress;

abstract class SessionSource
{
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public string $type;

    public SessionAddress $billing_address;

    public Phone $home_phone;

    public Phone $mobile_phone;

    public Phone $work_phone;

}
