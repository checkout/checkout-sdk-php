<?php

namespace Checkout\Marketplace;

use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Four\AccountHolder;
use Checkout\Common\Four\BankDetails;
use Checkout\Common\InstrumentType;

class MarketplacePaymentInstrument
{
    public function __construct()
    {
        $this->type = InstrumentType::$bank_account;
    }

    /**
     * @var InstrumentType
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
     * @var Currency
     */
    public $currency;

    /**
     * @var Country
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
