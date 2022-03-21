<?php

namespace Checkout\Instruments\Four\Update;

use Checkout\Common\InstrumentType;

class UpdateBankInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$bank_account);
    }

    //AccountType
    public $account_type;

    public $account_number;

    public $bank_code;

    public $branch_code;

    public $iban;

    public $bban;

    public $swift_bic;

    //Currency
    public $currency;

    //CountryCode
    public $country;

    public $processing_channel_id;

    // AccountHolder
    public $account_holder;

    // BankDetails
    public $bank_details;

    // UpdateCustomerRequest
    public $customer;
}
