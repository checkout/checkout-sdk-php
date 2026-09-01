<?php

namespace Checkout\Payments\Request\Source\Apm;

use Checkout\Common\AccountHolder;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Request\Source\AbstractRequestSource;

class RequestSepaSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$sepa);
    }

    /**
     * @var string values of Country
     */
    public $country;

    /**
     * @var string
     */
    public $account_number;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string
     */
    public $mandate_id;

    /**
     * @var string
     */
    public $date_of_signature;

    /**
     * @var AccountHolder
     */
    public $account_holder;

    /**
     * The type of mandate.
     * [Optional]
     * Enum: "Core" "B2B"
     *
     * The same two values as Checkout\Instruments\SepaMandateType, which you can use for the
     * constants. Kept as a string rather than bound to that class because this is a payments source,
     * not an instrument: the two schemas are independent and may diverge, as the SEPA and Bacs
     * payment_type fields already have.
     *
     * @var string|null $mandate_type
     */
    public $mandate_type;
}
