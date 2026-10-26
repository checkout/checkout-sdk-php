<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Sepa;

use Checkout\Common\DateOnly;
use DateTime;

/**
 * The SEPA mandate details.
 */
class SepaMandate
{
    /**
     * The ID of the mandate.
     * [Optional]
     * @var string
     */
    public $id;

    /**
     * The type of mandate.
     * [Optional]
     * @var string value of PaymentSetupSepaMandateType
     */
    public $type;

    /**
     * The date the mandate was signed.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $date_of_signature;
}
