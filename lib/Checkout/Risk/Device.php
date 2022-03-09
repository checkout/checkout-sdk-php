<?php

namespace Checkout\Risk;

use DateTime;

class Device
{
    public string $ip;

    public Location $location;

    public string $os;

    public string $type;

    public string $model;

    public DateTime $date;

    public string $user_agent;

    public string $fingerprint;
}
