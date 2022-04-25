<?php

namespace Checkout\Sessions\Source;

use Checkout\Common\Phone;
use Checkout\Sessions\SessionAddress;
use Checkout\Sessions\SessionScheme;
use Checkout\Sessions\SessionSourceType;

abstract class SessionSource
{
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @var SessionSourceType
     */
    public $type;

    /**
     * @var SessionAddress
     */
    public $billing_address;

    /**
     * @var Phone
     */
    public $home_phone;

    /**
     * @var Phone
     */
    public $mobile_phone;

    /**
     * @var Phone
     */
    public $work_phone;

    /**
     * @var SessionScheme
     */
    public $scheme;
}
