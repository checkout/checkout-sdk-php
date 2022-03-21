<?php

namespace Checkout\Sessions\Source;

use Checkout\Sessions\SessionSourceType;

class SessionCardSource extends SessionSource
{

    public function __construct()
    {
        parent::__construct(SessionSourceType::$card);
    }

    public $number;

    public $expiry_month;

    public $expiry_year;

    public $name;

    public $email;

}
