<?php

namespace Checkout\Instruments\Create;

use Checkout\Common\InstrumentType;

/**
 * Stores Bacs Direct Debit account details as a payment instrument.
 */
class CreateBacsInstrumentRequest extends CreateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$bacs);
    }

    /**
     * The account configuration for the instrument.
     * [Required]
     * @var CreateBacsInstrumentAccount
     */
    public $account;

    /**
     * The details of the Bacs Direct Debit account.
     * [Required]
     * @var CreateBacsInstrumentData
     */
    public $instrument_data;

    /**
     * The account holder details.
     * [Required]
     * @var CreateBacsAccountHolder
     */
    public $account_holder;

    /**
     * The customer details. Associates the instrument with an existing or new customer.
     * [Optional]
     * @var CreateCustomerInstrumentRequest
     */
    public $customer;
}
