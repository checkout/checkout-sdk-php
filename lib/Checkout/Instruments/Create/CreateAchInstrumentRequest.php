<?php

namespace Checkout\Instruments\Create;

use Checkout\Common\InstrumentType;

/**
 * Stores ACH bank account details as a payment instrument.
 *
 * The specification does not list type in the required array for this schema, unlike the Bacs Direct
 * Debit store request. The base class always sets it, so the difference is immaterial here.
 */
class CreateAchInstrumentRequest extends CreateInstrumentRequest
{
    public function __construct()
    {
        parent::__construct(InstrumentType::$ach);
    }

    /**
     * The details of the bank account.
     * [Required]
     * @var CreateAchInstrumentData
     */
    public $instrument_data;

    /**
     * The account holder details.
     * [Required]
     * @var CreateAchAccountHolder
     */
    public $account_holder;

    /**
     * The customer details. Associates the instrument with an existing or new customer.
     * [Optional]
     * @var CreateCustomerInstrumentRequest
     */
    public $customer;
}
