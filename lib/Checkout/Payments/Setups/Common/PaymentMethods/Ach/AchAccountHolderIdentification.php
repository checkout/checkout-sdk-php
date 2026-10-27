<?php

namespace Checkout\Payments\Setups\Common\PaymentMethods\Ach;

use Checkout\Common\EmptyAsObject;

/**
 * The ACH account holder's government document identification, for example a Social Security Number (SSN).
 *
 * Separate from Checkout\Common\AccountHolderIdentification: here the document type is free text.
 */
#[EmptyAsObject]
class AchAccountHolderIdentification
{
    /**
     * The document type.
     * [Optional]
     * @var string
     */
    public $type;

    /**
     * The country where the document was issued.
     * [Optional]
     * @var string
     */
    public $issuing_country;

    /**
     * The document number.
     * [Optional]
     * @var string
     */
    public $number;
}
