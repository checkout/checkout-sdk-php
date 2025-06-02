<?php

namespace Checkout\Forward\Requests\Sources;

class AbstractSource
{
    /**
     * @var string value of SourceType
     */
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }
}
