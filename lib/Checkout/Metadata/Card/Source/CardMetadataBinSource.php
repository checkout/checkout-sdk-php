<?php

namespace Checkout\Metadata\Card\Source;

class CardMetadataBinSource extends CardMetadataRequestSource
{
    /**
     * @var string
     */
    public $bin;

    public function __construct()
    {
        parent::__construct(CardMetadataSourceType::$BIN);
    }
}
