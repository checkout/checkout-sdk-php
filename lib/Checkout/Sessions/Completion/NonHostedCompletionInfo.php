<?php

namespace Checkout\Sessions\Completion;

class NonHostedCompletionInfo extends CompletionInfo
{
    public function __construct()
    {
        parent::__construct(CompletionInfoType::$nonHosted);
    }

    public string $callback_url;

}
