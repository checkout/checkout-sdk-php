<?php

namespace Checkout\Issuing\Disputes\Entities;

class Evidence
{
    /**
     * The complete file name, including the extension. (Required)
     *
     * @var string
     */
    public $name;

    /**
     * The base64-encoded string that represents a single JPG, PDF, TIFF, or ZIP file.
     * ZIP files can contain multiple JPG, PDF, or TIFF files. (Required)
     *
     * @var string
     */
    public $content;

    /**
     * A brief description of the evidence.
     *
     * @var string
     */
    public $description;
}
