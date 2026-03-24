<?php

namespace Checkout\NetworkTokens\Requests;

use Checkout\NetworkTokens\Entities\TransactionType;

class RequestCryptogramRequest
{
    /**
     * Transaction type the cryptogram is requested for. (Required)
     * Acceptable values: "ecom", "recurring", "pos", "aft"
     * Use TransactionType constants: TransactionType::$ecom, etc.
     *
     * @var string
     */
    public $transaction_type;
}
