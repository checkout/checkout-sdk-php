<?php

namespace Checkout\Instruments\Get;

use Checkout\Common\AbstractQueryFilter;
use Checkout\CheckoutUtils;
use DateTime;

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

    public function getEncodedQueryParameters(): string
    {
        $vars = get_object_vars($this);
        $query = [];

        foreach ($vars as $key => $value) {
            if ($value !== null) {
                $transformedKey = self::KEYS_TRANSFORMATIONS[$key] ?? $key;

                $query[$transformedKey] = $value instanceof DateTime
                    ? urlencode(CheckoutUtils::formatDate($value))
                    : $value;
            }
        }

        return http_build_query($query);
    }

}
