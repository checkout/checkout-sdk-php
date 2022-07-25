<?php

namespace Checkout\Instruments\Update;

abstract class UpdateInstrumentRequest
{
    /**
     * @var string value of InstrumentType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
