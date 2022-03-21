<?php

namespace Checkout\Risk;

class Device
{
    public $ip;

    // Location
    public $location;

    public $os;

    public $type;

    public $model;

    // DateTime
    public $date;

    public $user_agent;

    public $fingerprint;
}
