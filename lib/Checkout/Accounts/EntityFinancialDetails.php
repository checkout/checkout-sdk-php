<?php

namespace Checkout\Accounts;

class EntityFinancialDetails
{
    /**
     * @var int
     */
    public $annual_processing_volume;

    /**
     * @var int
     */
    public $average_transaction_value;

    /**
     * @var int
     */
    public $highest_transaction_value;

    /**
     * @var EntityFinancialDocuments
     */
    public $documents;
}
