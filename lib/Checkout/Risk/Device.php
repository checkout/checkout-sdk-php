<?php

namespace Checkout\Risk;

use DateTime;

class Device
{
    /**
     * @var string
     */
    public $ip;

    /**
     * @var Location
     */
    public $location;

    /**
     * @var string
     */
    public $os;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $model;

    /**
     * @var DateTime
     */
    public $date;

    /**
     * @var string
     */
    public $user_agent;

    /**
     * @var string
     */
    public $fingerprint;
}
