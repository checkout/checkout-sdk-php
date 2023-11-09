<?php

namespace Checkout;

use DateTime;
use GuzzleHttp\Psr7\Response;

class CheckoutUtils
{

    const PROJECT_NAME = "checkout-sdk-php";
    const PROJECT_VERSION = "3.0.18";

    /**
     * @param DateTime $date
     * @return string
     */
    public static function formatDate(DateTime $date)
    {
        return $date->format("Y-m-d\TH:i:sO");
    }

    /**
     * @param Response $http_response
     * @return HttpMetadata
     */
    public static function getHttpMetadata($http_response = null)
    {
        if ($http_response == null) {
            return null;
        }
        return new HttpMetadata(
            $http_response->getReasonPhrase(),
            $http_response->getStatusCode(),
            $http_response->getHeaders(),
            $http_response->getProtocolVersion()
        );
    }
}
