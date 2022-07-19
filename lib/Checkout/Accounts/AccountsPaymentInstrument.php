<?php

namespace Checkout\Accounts;

use Checkout\Common\Four\AccountHolder;
use Checkout\Common\Four\BankDetails;
use Checkout\Common\InstrumentType;

class AccountsPaymentInstrument
{
    public function __construct()
    {
        $this->type = InstrumentType::$bank_account;
    }

    /**
     * @var string value of InstrumentType
     */
    public $type;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
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
    public $bban;

    /**
     * @var string
     */
    public $swift_bic;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string values of Country
     */
    public $country;

    /**
     * @var InstrumentDocument
     */
    public $document;

    /**
     * @var BankDetails
     */
    public $bank;

    /**
     * @var AccountHolder
     */
    public $account_holder;
}