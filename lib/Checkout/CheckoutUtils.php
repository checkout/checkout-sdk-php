<?php

namespace Checkout;

use DateTime;
use DateTimeInterface;

class CheckoutUtils
{

    public const PROJECT_NAME = "checkout-sdk-php";
    public const PROJECT_VERSION = "2.0.0-beta3";

    public static function formatDate(DateTime $date): string
    {
        return $date->format(DateTimeInterface::ISO8601);
    }

}
