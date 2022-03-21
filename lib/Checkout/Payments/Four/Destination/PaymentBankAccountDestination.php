<?php

namespace Checkout\Payments\Four\Destination;

use Checkout\Payments\PaymentDestinationType;

class PaymentBankAccountDestination extends PaymentRequestDestination
{

    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$bank_account);
    }

    //AccountType
    public $account_type;

    public $account_number;

    public $bank_code;

    public $branch_code;

    public $iban;

    public $swift_bic;

    public $country;

    // AccountHolder
    public $account_holder;

    // BankDetails
    public $bank;

}
