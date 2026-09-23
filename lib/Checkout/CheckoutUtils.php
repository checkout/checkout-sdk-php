<?php

namespace Checkout;

use DateTime;
use GuzzleHttp\Psr7\Response;

class CheckoutUtils
{

    const PROJECT_NAME = "checkout-sdk-php";
    const PROJECT_VERSION = "6.4.0";

    /**
     * @param DateTime $date
     * @return string
     */
    public static function formatDate(DateTime $date): string
    {
        return $date->format("Y-m-d\TH:i:sO");
    }

    /**
     * Formats a date-only value as yyyy-MM-dd.
     *
     * Used for swagger fields declared `format: date` rather than `format: date-time`. The API
     * rejects a full date-time on those fields with 400 request_body_malformed.
     *
     * @param DateTime $date
     * @return string
     */
    public static function formatDateOnly(DateTime $date): string
    {
        return $date->format("Y-m-d");
    }

    /**
     * @param Response|null $http_response
     * @return HttpMetadata
     */
    public static function getHttpMetadata(?Response $http_response = null): ?HttpMetadata
    {
        if (!$http_response) {
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
