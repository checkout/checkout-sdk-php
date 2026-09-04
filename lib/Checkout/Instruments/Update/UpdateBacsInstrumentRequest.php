<?php

namespace Checkout\Instruments\Update;

use Checkout\Common\InstrumentType;

/**
 * Updates the details of a stored Bacs Direct Debit instrument.
 */
class UpdateBacsInstrumentRequest extends UpdateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$bacs);
    }

    /**
     * The details of the Bacs Direct Debit account.
     * [Optional]
     * @var UpdateBacsInstrumentData
     */
    public $instrument_data;

    /**
     * The account holder details.
     * [Optional]
     * @var UpdateBacsAccountHolder
     */
    public $account_holder;
}
