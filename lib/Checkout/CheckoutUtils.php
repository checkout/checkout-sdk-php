<?php

namespace Checkout;

use DateTime;
use GuzzleHttp\Psr7\Response;

class CheckoutUtils
{

    const PROJECT_NAME = "checkout-sdk-php";
    const PROJECT_VERSION = "6.11.0";

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
     * Builds a query string from the given parameters, each value percent-encoded as defined in
     * RFC 3986. Null values are left out; false, 0 and empty strings are sent. Dates are formatted
     * with formatDate and booleans as "true" or "false".
     *
     * @param array $parameters query parameter names and their values
     * @return string the query string without the leading "?"
     * @throws CheckoutArgumentException if a value is not a string, a number, a boolean or a DateTime
     */
    public static function buildQuery(array $parameters): string
    {
        $rendered = array();
        foreach ($parameters as $name => $value) {
            if ($value === null) {
                continue;
            }
            $rendered[$name] = self::renderQueryValue($value);
        }
        return http_build_query($rendered, "", "&", PHP_QUERY_RFC3986);
    }

    /**
     * Renders a single query value before encoding.
     *
     * Booleans must be emitted as "true"/"false": http_build_query would render them as
     * "1"/"0", which the API rejects with a 400 invalid_request.
     *
     * @param mixed $value
     * @return string|int|float
     * @throws CheckoutArgumentException if the value is not a string, a number, a boolean or a DateTime
     */
    private static function renderQueryValue($value)
    {
        if ($value instanceof DateTime) {
            return self::formatDate($value);
        }
        if (is_bool($value)) {
            return $value ? "true" : "false";
        }
        if (is_string($value) || is_int($value) || is_float($value)) {
            return $value;
        }
        throw new CheckoutArgumentException("invalid query parameter");
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
