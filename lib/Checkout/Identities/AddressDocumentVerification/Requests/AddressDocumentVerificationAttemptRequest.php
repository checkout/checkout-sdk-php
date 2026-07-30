<?php

namespace Checkout\Identities\AddressDocumentVerification\Requests;

class AddressDocumentVerificationAttemptRequest
{
    /**
     * The address document image to upload.
     * [Required]
     * Format: binary
     * @var string
     */
    public $document;
}
