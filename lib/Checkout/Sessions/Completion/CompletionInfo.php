<?php

namespace Checkout\Sessions\Completion;

abstract class CompletionInfo
{
    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public string $type;

}
