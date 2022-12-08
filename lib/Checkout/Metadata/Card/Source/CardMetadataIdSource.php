<?php

namespace Checkout\Metadata\Card\Source;

class CardMetadataIdSource extends CardMetadataRequestSource
{
    /**
     * @var string
     */
    public $id;

    public function __construct()
    {
        parent::__construct(CardMetadataSourceType::$ID);
    }
}
