<?php

namespace Checkout\Files;

class FileRequest
{
    /**
     * Absolute or relative path to the file to upload.
     * Must be a path controlled by your application (e.g. from a validated upload);
     * do not use paths taken directly from user input without validation.
     *
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $purpose;
}
