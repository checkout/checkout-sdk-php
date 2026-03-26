<?php

namespace Checkout\StandaloneAccountUpdater\Entities;

class Card
{
    /**
     * The card number. (Required)
     *
     * @var string
     */
    public $number;

    /**
     * The expiry month of the card (Required)
     *
     * @var int
     */
    public $expiry_month;

    /**
     * The four-digit expiry year of the card (Required)
     *
     * @var int
     */
    public $expiry_year;
}
