<?php

namespace Checkout\Accounts;

class InstrumentDetailsAch implements InstrumentDetails
{
    /**
     * The alphanumeric value that identifies the account.
     * [Required]
     * @var string
     */
    public $account_number;

    /**
     * The 9-digit American Bankers Association (ABA) routing number that identifies the financial institution.
     * [Required]
     * ^[0-9]{9}$
     * @var string
     */
    public $routing_number;

    /**
     * The type of bank account.
     * [Required]
     * Enum: value of InstrumentAccountType
     * @var string
     */
    public $account_type;
}
