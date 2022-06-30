<?php

namespace Checkout\Instruments\Four\Create;

abstract class CreateInstrumentRequest
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
