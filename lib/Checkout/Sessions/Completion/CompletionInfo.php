<?php

namespace Checkout\Sessions\Completion;

abstract class CompletionInfo
{
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @var CompletionInfoType
     */
    public $type;
}
