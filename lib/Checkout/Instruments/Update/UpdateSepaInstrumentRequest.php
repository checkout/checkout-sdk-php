<?php

namespace Checkout\Instruments\Update;

use Checkout\Common\InstrumentType;

/**
 * Updates the details of a stored SEPA instrument.
 */
class UpdateSepaInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$sepa);
    }

    /**
     * The details of the SEPA account.
     * [Optional]
     * @var UpdateSepaInstrumentData
     */
    public $instrument_data;

    /**
     * The account holder details.
     * [Optional]
     * @var UpdateSepaAccountHolder
     */
    public $account_holder;
}
