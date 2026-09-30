<?php

namespace Checkout\Accounts;

/**
 * Shareholder structure chart (including % of shares) certified by a competent authority
 * individual and dated within the last 3 months.
 */
class ShareholderStructure
{
    /**
     * The type of document.
     * [Required]
     * Enum: "certified_shareholder_structure"
     *
     * @var string value of ShareholderStructureType
     */
    public $type;

    /**
     * The ID of the front side of the document as represented within Checkout.com systems.
     * [Required]
     * ^file_[a-z2-7]{26}$
     * Length: 31 characters
     *
     * @var string
     */
    public $front;
}
