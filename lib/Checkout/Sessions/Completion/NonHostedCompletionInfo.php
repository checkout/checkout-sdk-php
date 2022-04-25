<?php

namespace Checkout\Sessions\Completion;

class NonHostedCompletionInfo extends CompletionInfo
{
    public function __construct()
    {
        parent::__construct(CompletionInfoType::$nonHosted);
    }

    /**
     * @var string
     */
    public $callback_url;
}
