<?php

namespace Checkout\Instruments\Create;

use Checkout\Common\AccountHolder;
use Checkout\Common\InstrumentType;

class CreateTokenInstrumentRequest extends CreateInstrumentRequest
{

    public function __construct()
    {
        parent::__construct(InstrumentType::$token);
    }

    /**
     * @var string
     */
    public $token;

    /**
     * @var AccountHolder
     */
    public $account_holder;

    /**
     * @var CreateCustomerInstrumentRequest
     */
    public $customer;
}
