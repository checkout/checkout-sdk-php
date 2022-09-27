<?php

namespace Checkout\Sessions\Source;

use Checkout\Common\Phone;

abstract class SessionSource
{
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @var string value of SessionSourceType
     */
    public $type;

    /**
     * @var string value of SessionScheme
     */
    public $scheme;

    /**
     * @var string value of SessionAddress
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
     * @var string
     */
    public $email;
}
