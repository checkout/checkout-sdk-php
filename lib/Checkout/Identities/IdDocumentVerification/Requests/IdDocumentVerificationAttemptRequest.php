<?php

namespace Checkout\Identities\IdDocumentVerification\Requests;

class IdDocumentVerificationAttemptRequest
{
    /**
     * The image of the front of the document to upload. (Required)
     *
     * @var string
     */
    public $document_front;

    /**
     * The image of the back of the document to upload.
     *
     * @var string
     */
    public $document_back;
}
