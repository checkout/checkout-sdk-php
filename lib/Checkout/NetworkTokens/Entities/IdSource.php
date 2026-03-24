<?php

namespace Checkout\NetworkTokens\Entities;

class IdSource extends Source
{
    /**
     * The card instrument ID.
     *
     * @var string
     */
    public $id;

    public function __construct()
    {
        parent::__construct(SourceType::$id);
    }
}
