<?php

namespace Common;

class RequestMetrics
{
    public $prevRequestId;
    public $requestId;
    public $prevRequestDuration;

    public function __construct($prevRequestId, $requestId, $prevRequestDuration)
    {
        $this->prevRequestId = $prevRequestId;
        $this->requestId = $requestId;
        $this->prevRequestDuration = $prevRequestDuration;
    }
}
