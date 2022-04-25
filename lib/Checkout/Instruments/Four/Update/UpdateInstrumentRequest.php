<?php

namespace Checkout\Instruments\Four\Update;

use Checkout\Common\InstrumentType;

abstract class UpdateInstrumentRequest
{
    /**
     * @var InstrumentType
     */
    public $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }
}
