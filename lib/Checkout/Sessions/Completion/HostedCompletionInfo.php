<?php

namespace Checkout\Sessions\Completion;

class HostedCompletionInfo extends CompletionInfo
{
    public function __construct()
    {
        parent::__construct(CompletionInfoType::$hosted);
    }

    public $callback_url;

    public $success_url;

    public $failure_url;

}
