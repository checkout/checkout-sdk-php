<?php

namespace Checkout\Accounts;

/**
 * Shareholder structure chart, including the percentage of shares, certified by a competent
 * authority and dated within the last 3 months.
 */
class ShareholderStructure
{
    /**
     * @var string value of ShareholderStructureType
     */
    public $type;

    /**
     * @var string the ID of the front side of the document, as returned when the file was uploaded
     */
    public $front;
}
