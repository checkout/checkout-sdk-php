<?php

namespace Checkout\Instruments\Four\Get;

use Checkout\Common\AbstractQueryFilter;

class BankAccountFieldQuery extends AbstractQueryFilter
{
    private const KEYS_TRANSFORMATIONS = array(
        "account_holder_type" => "account-holder-type",
        "payment_network" => "payment-network"
    );

    public ?string $account_holder_type = null;
    public ?string $payment_network = null;

    public function normalized(): BankAccountFieldQuery
    {
        $normalized = new BankAccountFieldQuery();
        foreach (self::KEYS_TRANSFORMATIONS as $originalKey => $modifiedKey) {
            unset($normalized->$originalKey);
            if ($this->{$originalKey}) {
                /** @phpstan-ignore-next-line */
                $normalized->$modifiedKey = $this->$originalKey;
            }
        }
        return $normalized;
    }

}
