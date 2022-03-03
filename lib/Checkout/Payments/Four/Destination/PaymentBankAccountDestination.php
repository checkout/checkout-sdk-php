<?php

namespace Checkout\Payments\Four\Destination;

use Checkout\Common\Four\AccountHolder;
use Checkout\Common\Four\BankDetails;
use Checkout\Payments\PaymentDestinationType;

class PaymentBankAccountDestination extends PaymentRequestDestination
{

    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$bank_account);
    }

    //AccountType
    public string $account_type;

    public string $account_number;

    public string $bank_code;

    public string $iban;

    public string $swift_bic;

    public string $country;

    public AccountHolder $account_holder;

    public BankDetails $bank;

}
