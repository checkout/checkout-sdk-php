<?php

namespace Checkout\Metadata\Card;

use Checkout\Metadata\Card\Source\CardMetadataRequestSource;

class CardMetadataRequest
{
    /**
     * @var CardMetadataRequestSource
     */
    public $source;
    /**
     * @var string value of CardMetadataFormatType
     */
    public $format;
}
