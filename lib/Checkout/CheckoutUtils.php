<?php

namespace Checkout;

use DateTime;

class CheckoutUtils
{

    const PROJECT_NAME = "checkout-sdk-php";
    const PROJECT_VERSION = "2.0.0-beta5";

    public static function formatDate(DateTime $date)
    {
        return $date->format("Y-m-d\TH:i:sO");
    }

}
