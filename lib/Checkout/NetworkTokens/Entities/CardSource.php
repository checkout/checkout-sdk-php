<?php

namespace Checkout\NetworkTokens\Entities;

class CardSource extends Source
{
    /**
     * The card number. (Required)
     *
     * @var string
     */
    public $number;

    /**
     * The expiry month of the card. (Required)
     *
     * @var string
     */
    public $expiry_month;

    /**
     * The four-digit expiry year of the card. (Required)
     *
     * @var string
     */
    public $expiry_year;

    /**
     * The CVV number for the card.
     *
     * @var string
     */
    public $cvv;

    public function __construct()
    {
        parent::__construct(SourceType::$card);
    }
}
