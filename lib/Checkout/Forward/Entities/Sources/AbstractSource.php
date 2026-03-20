<?php

namespace Checkout\Forward\Entities\Sources;

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
