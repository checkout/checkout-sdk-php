<?php

namespace Checkout\Common;

class RequestMetrics
{
    public $prevRequestId;
    public $requestId;
    public $prevRequestDuration;

    public function __construct($prevRequestId = null, $requestId = null, $prevRequestDuration = null)
    {
        $this->prevRequestId = $prevRequestId;
        $this->requestId = $requestId;
        $this->prevRequestDuration = $prevRequestDuration;
    }
}
