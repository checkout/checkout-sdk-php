<?php

namespace Checkout\Common;

use Checkout\CheckoutArgumentException;
use Checkout\CheckoutUtils;

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
        return CheckoutUtils::buildQuery(get_object_vars($this));
    }
}
