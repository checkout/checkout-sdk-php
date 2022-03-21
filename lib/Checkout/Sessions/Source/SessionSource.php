<?php

namespace Checkout\Sessions\Source;

abstract class SessionSource
{
    public function __construct($type)
    {
        $this->type = $type;
    }

    public $type;

    // SessionAddress
    public $billing_address;

    public $home_phone;

    public $mobile_phone;

    public $work_phone;

}
