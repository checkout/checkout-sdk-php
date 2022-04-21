<?php

namespace Checkout;

use DateTime;

class CheckoutUtils
{

    const PROJECT_NAME = "checkout-sdk-php";
    const PROJECT_VERSION = "2.1.1";

    public static function formatDate(DateTime $date)
    {
        return $date->format("Y-m-d\TH:i:sO");
    }
}
