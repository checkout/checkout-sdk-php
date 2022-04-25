<?php

namespace Checkout\Sessions\Completion;

class HostedCompletionInfo extends CompletionInfo
{
    public function __construct()
    {
        parent::__construct(CompletionInfoType::$hosted);
    }

    /**
     * @var string
     */
    public $callback_url;

    /**
     * @var string
     */
    public $success_url;

    /**
     * @var string
     */
    public $failure_url;
}
