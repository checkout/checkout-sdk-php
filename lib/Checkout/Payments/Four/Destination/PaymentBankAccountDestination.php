<?php

namespace Checkout\Payments\Four\Destination;

use Checkout\Common\Country;
use Checkout\Common\Four\AccountHolder;
use Checkout\Common\Four\AccountType;
use Checkout\Common\Four\BankDetails;
use Checkout\Payments\PaymentDestinationType;

class PaymentBankAccountDestination extends PaymentRequestDestination
{

    public function __construct()
    {
        parent::__construct(PaymentDestinationType::$bank_account);
    }

    /**
     * @var AccountType
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
     * @var Country
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
