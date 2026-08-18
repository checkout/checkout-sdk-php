<?php

namespace Checkout\Accounts;

/**
 * A document showing transactions from the last 3 months.
 */
class BankVerification
{
    /**
     * @var string value of BankVerificationType
     */
    public $type;

    /**
     * @var string the ID of the front side of the document, as returned when the file was uploaded
     */
    public $front;
}
