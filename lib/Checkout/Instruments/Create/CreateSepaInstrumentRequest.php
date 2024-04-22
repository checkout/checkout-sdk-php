<?php

namespace Checkout\Instruments\Create;

use Checkout\Common\AccountHolder;
use Checkout\Common\InstrumentType;

class CreateSepaInstrumentRequest extends CreateInstrumentRequest
{

    public function __construct()
    {
        parent::__construct(InstrumentType::$sepa);
    }

    /**
     * @var InstrumentData
     */
    public $instrument_data;

    /**
     * @var AccountHolder
     */
    public $account_holder;

}
