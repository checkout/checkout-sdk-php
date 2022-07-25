<?php

namespace Checkout\Instruments\Get;

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
