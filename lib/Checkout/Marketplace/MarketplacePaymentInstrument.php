<?php

namespace Checkout\Marketplace;

use Checkout\Common\Four\BankDetails;
use Checkout\Common\InstrumentType;

class MarketplacePaymentInstrument
{
    public function __construct()
    {
        $this->type = InstrumentType::$bank_account;
    }

    public string $type;

    public string $label;

    public string $account_type;

    public string $account_number;

    public string $bank_code;

    public string $branch_code;

    public string $iban;

    public string $bban;

    public string $swift_bic;

    public string $currency;

    public string $country;

    public InstrumentDocument $document;

    public BankDetails $bank;

    public MarketplaceAccountHolder $account_holder;
}
