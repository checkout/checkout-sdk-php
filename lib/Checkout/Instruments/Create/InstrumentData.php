<?php

namespace Checkout\Instruments\Create;

class InstrumentData
{
    /**
     * @var string
     */
    public $account_number;

    /**
     * @var string values of Country
     */
    public $country;

    /**
     * @var string values of Currency
     */
    public $currency;

    /**
     * @var string values of PaymentType
     */
    public $payment_type;

    /**
     * @var string
     */
    public $mandate_id;

    /**
     * @var DateTime
     */
    public $date_of_signature;
}
