<?php

namespace Checkout\Metadata\Card\Source;

abstract class CardMetadataRequestSource
{
    /**
     * @var string value of CardMetadataSourceType
     */
    public $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

}
