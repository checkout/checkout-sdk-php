<?php

namespace Checkout\Accounts;

/**
 * Additional space for documents to be provided when requested. Carries a file ID only; the API
 * defines no document type for it.
 */
class AdditionalDocument
{
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
