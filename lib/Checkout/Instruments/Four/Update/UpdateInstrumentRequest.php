<?php

namespace Checkout\Instruments\Four\Update;

abstract class UpdateInstrumentRequest
{
    //InstrumentType
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }

}
