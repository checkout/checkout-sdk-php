<?php

namespace Checkout\Sessions\Completion;

class HostedCompletionInfo extends CompletionInfo
{
    public function __construct()
    {
        parent::__construct(CompletionInfoType::$hosted);
    }

    public string $callback_url;

    public string $success_url;

    public string $failure_url;

}
