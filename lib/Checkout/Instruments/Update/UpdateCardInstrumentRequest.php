<?php

namespace Checkout\Instruments\Update;

use Checkout\Common\AccountHolder;
use Checkout\Common\InstrumentType;

class UpdateCardInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$card);
    }

    /**
     * @var int
     */
    public $expiry_month;

    /**
     * @var int
     */
    public $expiry_year;

    /**
     * @var string
     */
    public $name;

    /**
     * @var UpdateCustomerRequest
     */
    public $customer;

    /**
     * @var AccountHolder
     */
    public $account_holder;
}
