<?php

namespace Checkout\Accounts\Files\Requests;

class UploadFileRequest
{
    /**
     * The purpose of the file upload. (Required)
     * Use values from the FilePurpose class to set values to this field.
     * @var string
     */
    public $purpose;
}
