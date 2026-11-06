<?php

namespace Checkout\Instruments\Get;

use Checkout\CheckoutArgumentException;
use Checkout\Common\AbstractQueryFilter;

class BankAccountFieldQuery extends AbstractQueryFilter
{
    const KEYS_TRANSFORMATIONS = array(
        "account_holder_type" => "account-holder-type",
        "payment_network" => "payment-network"
    );

    /**
     * @var string|null value of AccountHolderType
     */
    public $account_holder_type = null;

    /**
     * @var string|null value of PaymentNetwork
     */
    public $payment_network = null;

    /**
     * Builds the query string with the parameter names the API expects (account-holder-type,
     * payment-network), each value percent-encoded as defined in RFC 3986.
     *
     * @return string
     * @throws CheckoutArgumentException if a property holds a value that is not a string, a number,
     *     a boolean or a DateTime
     */
    public function getEncodedQueryParameters(): string
    {
        $vars = get_object_vars($this);
        $query = [];

        foreach ($vars as $key => $value) {
            if ($value !== null) {
                $transformedKey = self::KEYS_TRANSFORMATIONS[$key] ?? $key;

                $query[$transformedKey] = $this->renderValue($value);
            }
        }

        return http_build_query($query, "", "&", PHP_QUERY_RFC3986);
    }

}
