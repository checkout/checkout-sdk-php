<?php

namespace Checkout\NetworkTokens\Entities;

abstract class Source
{
    /**
     * The source type.
     *
     * @var string
     */
    public $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }
}
