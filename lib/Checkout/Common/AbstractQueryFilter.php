<?php

namespace Checkout\Common;

use Checkout\CheckoutUtils;
use DateTime;

abstract class AbstractQueryFilter
{

    public function getEncodedQueryParameters()
    {
        // Join only the parameters that are actually set. The previous implementation appended
        // "&" whenever the current key was not the last *declared* property, which emitted a
        // trailing "&" whenever the last declared property was unset.
        $parts = array();
        foreach (get_object_vars($this) as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $parts[] = $key . "=" . $this->encodeValue($value);
        }
        return implode("&", $parts);
    }

    /**
     * Renders a single query-parameter value.
     *
     * Booleans must be emitted as "true"/"false": PHP string concatenation would render them as
     * "1"/"0", which the API rejects with a 400 invalid_request.
     *
     * @param mixed $value
     * @return string
     */
    private function encodeValue($value)
    {
        if ($value instanceof DateTime) {
            return urlencode(CheckoutUtils::formatDate($value));
        }
        if (is_bool($value)) {
            return $value ? "true" : "false";
        }
        return $value;
    }
}
