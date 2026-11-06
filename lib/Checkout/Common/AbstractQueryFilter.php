<?php

namespace Checkout\Common;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutUtils;
use DateTime;

abstract class AbstractQueryFilter
{

    /**
     * Builds the query string from every property that is not null, each value percent-encoded
     * as defined in RFC 3986. false, 0 and empty strings are sent.
     *
     * @return string
     * @throws CheckoutArgumentException if a property holds a value that is not a string, a number,
     *     a boolean or a DateTime
     */
    public function getEncodedQueryParameters()
    {
        $parameters = array();
        foreach (get_object_vars($this) as $key => $value) {
            if ($value === null) {
                continue;
            }
            $parameters[$key] = $this->renderValue($value);
        }
        return http_build_query($parameters, "", "&", PHP_QUERY_RFC3986);
    }

    /**
     * Renders a single query-parameter value before encoding.
     *
     * Booleans must be emitted as "true"/"false": http_build_query would render them as
     * "1"/"0", which the API rejects with a 400 invalid_request.
     *
     * @param mixed $value
     * @return string|int|float
     * @throws CheckoutArgumentException if the value is not a string, a number, a boolean or a DateTime
     */
    protected function renderValue($value)
    {
        if ($value instanceof DateTime) {
            return CheckoutUtils::formatDate($value);
        }
        if (is_bool($value)) {
            return $value ? "true" : "false";
        }
        if (is_string($value) || is_int($value) || is_float($value)) {
            return $value;
        }
        throw new CheckoutArgumentException("invalid query parameter");
    }
}
