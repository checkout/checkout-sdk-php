<?php

namespace Checkout\StandaloneAccountUpdater\Entities;

class SourceOptions
{
    /**
     * The card details
     *
     * @var Card
     */
    public $card;

    /**
     * Instrument reference
     *
     * @var Instrument
     */
    public $instrument;
}
