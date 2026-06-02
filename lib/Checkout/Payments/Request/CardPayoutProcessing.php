<?php

namespace Checkout\Payments\Request;

class CardPayoutProcessing
{
    /**
     * The unique identifier for Visa-registered ramp providers.
     * Must only contain alphanumeric characters.
     * Required if you're a Visa-registered ramp provider operating with affiliates.
     * [Optional]
     * Pattern: ^[a-zA-Z0-9]{1,15}$
     * max 15 characters
     * @var string|null $affiliate_id
     */
    public $affiliate_id;

    /**
     * The affiliate URL.
     * Required if you're a Visa-registered ramp provider operating with affiliates.
     * [Optional]
     * @var string|null $affiliate_url
     */
    public $affiliate_url;

    /**
     * The speed at which the payout is processed.
     * Only applicable for unreferenced refunds.
     * [Optional]
     * Enum: "fast"
     * @var string|null $processing_speed
     */
    public $processing_speed;

    /**
     * The two-letter ISO country code of the purchase country.
     * Required if you're a Visa-registered ramp provider operating with affiliates.
     * [Optional]
     * max 2 characters
     * @var string|null $purchase_country
     */
    public $purchase_country;
}
