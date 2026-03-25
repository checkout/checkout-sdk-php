<?php

namespace Checkout\NetworkTokens\Requests;

use Checkout\NetworkTokens\Entities\InitiatedByType;
use Checkout\NetworkTokens\Entities\ReasonType;

class DeleteNetworkTokenRequest
{
    /**
     * Who initiated/requested the deletion of the token. (Required)
     * Acceptable values: "cardholder", "token_requestor"
     * Use InitiatedByType constants: InitiatedByType::$cardholder, etc.
     *
     * @var string
     */
    public $initiated_by;

    /**
     * The reason for deleting the token. (Required)
     * Acceptable values: "fraud", "other"
     * Use ReasonType constants: ReasonType::$fraud, etc.
     *
     * @var string
     */
    public $reason;
}
