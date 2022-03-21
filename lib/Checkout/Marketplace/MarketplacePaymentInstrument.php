<?php

namespace Checkout\Marketplace;

use Checkout\Common\InstrumentType;

class MarketplacePaymentInstrument
{
    public function __construct()
    {
        $this->type = InstrumentType::$bank_account;
    }

    public $type;

    public $label;

    public $account_type;

    public $account_number;

    public $bank_code;

    public $branch_code;

    public $iban;

    public $bban;

    public $swift_bic;

    public $currency;

    public $country;

    // InstrumentDocument
    public $document;

    // BankDetails
    public $bank;

    // MarketplaceAccountHolder
    public $account_holder;
}
