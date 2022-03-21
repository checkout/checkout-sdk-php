<?php

namespace Checkout\Instruments\Four\Get;

use Checkout\Common\AbstractQueryFilter;

class BankAccountFieldQuery extends AbstractQueryFilter
{
    const KEYS_TRANSFORMATIONS = array(
        "account_holder_type" => "account-holder-type",
        "payment_network" => "payment-network"
    );

    public $account_holder_type = null;
    public $payment_network = null;

    public function normalized()
    {
        $normalized = new BankAccountFieldQuery();
        foreach (self::KEYS_TRANSFORMATIONS as $originalKey => $modifiedKey) {
            unset($normalized->$originalKey);
            if ($this->{$originalKey}) {
                $normalized->$modifiedKey = $this->$originalKey;
            }
        }
        return $normalized;
    }

}
