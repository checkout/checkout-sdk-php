<?php

namespace Checkout\Instruments\Four\Create;

use Checkout\Common\Four\AccountHolder;
use Checkout\Common\Four\BankDetails;
use Checkout\Common\InstrumentType;

class CreateBankAccountInstrumentRequest extends CreateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$bank_account);
    }

    //AccountType
    public string $account_type;

    public string $account_number;

    public string $bank_code;

    public string $branch_code;

    public string $iban;

    public string $bban;

    public string $swift_bic;

    //Currency
    public string $currency;

    //CountryCode
    public string $country;

    public string $processing_channel_id;

    public AccountHolder $account_holder;

    public BankDetails $bank_details;

    public CreateCustomerInstrumentRequest $customer;

}
