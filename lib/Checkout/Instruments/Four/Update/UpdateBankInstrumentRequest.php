<?php

namespace Checkout\Instruments\Four\Update;

use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Four\AccountHolder;
use Checkout\Common\Four\AccountType;
use Checkout\Common\Four\BankDetails;
use Checkout\Common\InstrumentType;

class UpdateBankInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$bank_account);
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
    public $bban;

    /**
     * @var string
     */
    public $swift_bic;

    /**
     * @var Currency
     */
    public $currency;

    /**
     * @var Country
     */
    public $country;

    /**
     * @var string
     */
    public $processing_channel_id;

    /**
     * @var AccountHolder
     */
    public $account_holder;

    /**
     * @var BankDetails
     */
    public $bank_details;

    /**
     * @var UpdateCustomerRequest
     */
    public $customer;
}
