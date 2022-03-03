<?php

namespace Checkout\Instruments\Four\Update;

abstract class UpdateInstrumentRequest
{
    //InstrumentType
    public string $type;

    protected function __construct(string $type)
    {
        $this->type = $type;
    }

}
