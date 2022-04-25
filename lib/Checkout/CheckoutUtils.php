<?php

namespace Checkout;

use DateTime;

class CheckoutUtils
{

    const PROJECT_NAME = "checkout-sdk-php";
    const PROJECT_VERSION = "2.1.1";

    /**
     * @param DateTime $date
     * @return string
     */
    public static function formatDate(DateTime $date)
    {
        return $date->format("Y-m-d\TH:i:sO");
    }
}
