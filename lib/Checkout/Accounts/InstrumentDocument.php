<?php

namespace Checkout\Accounts;

class InstrumentDocument
{
    /**
     * The document type. For a bank account instrument the API accepts only
     * InstrumentDocumentType::$bank_statement, which is also the default.
     *
     * @var string value of InstrumentDocumentType
     */
    public $type;

    /**
     * @var string
     */
    public $file_id;
}
