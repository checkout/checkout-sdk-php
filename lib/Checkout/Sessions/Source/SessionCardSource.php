<?php

namespace Checkout\Sessions\Source;

use Checkout\Sessions\SessionSourceType;

class SessionCardSource extends SessionSource
{

    public function __construct()
    {
        parent::__construct(SessionSourceType::$card);
    }

    public string $number;

    public int $expiry_month;

    public int $expiry_year;

    public string $name;

    public string $email;

}
