<?php

namespace Checkout\NetworkTokens\Requests;

use Checkout\NetworkTokens\Entities\Source;

class ProvisionNetworkTokenRequest
{
    /**
     * The source object (either IdSource or CardSource). (Required)
     *
     * @var Source
     */
    public $source;
}
