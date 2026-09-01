<?php

namespace Checkout\Instruments\Update;

use Checkout\Common\InstrumentType;

/**
 * Updates the details of a stored ACH instrument.
 *
 * Nothing in this request is required by the specification.
 */
class UpdateAchInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$ach);
    }

    /**
     * The details of the bank account.
     * [Optional]
     * @var UpdateAchInstrumentData
     */
    public $instrument_data;

    /**
     * The account holder details.
     * [Optional]
     * @var UpdateAchAccountHolder
     */
    public $account_holder;
}
