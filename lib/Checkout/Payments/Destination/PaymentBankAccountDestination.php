<?php

namespace Checkout\Payments\Destination;

use Checkout\Common\AccountHolder;
use Checkout\Common\BankDetails;
use Checkout\Payments\PaymentDestinationType;

class PaymentBankAccountDestination extends PaymentRequestDestination
{

    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$bank_account);
    }

    /**
     * @var string value of AccountType
     */
    public $account_type;

    /**
     * @var string
     */
    public $account_number;

    /**
     * @var string
     */
    public $bank_code;

    /**
     * @var string
     */
    public $branch_code;

    /**
     * @var string
     */
    public $iban;

    /**
     * @var string
     */
    public $swift_bic;

    /**
     * @var string values of Country
     */
    public $country;

    /**
     * @var AccountHolder
     */
    public $account_holder;

    /**
     * @var BankDetails
     */
    public $bank;
}
