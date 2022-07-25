<?php

namespace Checkout\Instruments\Create;

use Checkout\Common\AccountHolder;
use Checkout\Common\BankDetails;
use Checkout\Common\InstrumentType;

class CreateBankAccountInstrumentRequest extends CreateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$bank_account);
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
     * @var CreateCustomerInstrumentRequest
     */
    public $customer;
}
