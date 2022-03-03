<?php

namespace Checkout\Common;

use Checkout\CheckoutUtils;
use DateTime;

abstract class AbstractQueryFilter
{

    public function getEncodedQueryParameters(): string
    {
        $url = "";
        $vars = get_object_vars($this);
        if (!empty($vars)) {
            foreach ($vars as $key => $value) {
                if (!empty($value)) {
                    $url .= $key . "=";
                    $url .= $value instanceof DateTime ? urlencode(CheckoutUtils::formatDate($value)) : $value;
                }
                if ($key != array_key_last($vars)) {
                    $url .= "&";
                }
            }
        }
        return $url;
    }
}
