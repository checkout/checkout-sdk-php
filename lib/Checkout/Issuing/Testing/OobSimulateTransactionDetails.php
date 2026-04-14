<?php

namespace Checkout\Issuing\Testing;

/**
 * Details for the simulated transaction (OpenAPI OobSimulateTransactionDetails).
 */
class OobSimulateTransactionDetails
{
    /**
     * The last four digits of the card number, also known as the PAN.
     * [Optional]
     * Pattern: ^[0-9]{4}$
     * Length: 4 characters
     *
     * @var string|null
     */
    public $last_four;

    /**
     * The merchant's name.
     * [Optional]
     *
     * @var string|null
     */
    public $merchant_name;

    /**
     * The amount of the simulated transaction.
     * [Optional]
     *
     * @var float|null
     */
    public $purchase_amount;

    /**
     * The issuing currency as a three-letter ISO 4217 code.
     * [Optional]
     * Pattern: ^[a-zA-Z]{3}$
     * Length: 3 characters
     * Format: ISO4217
     *
     * @var string|null
     */
    public $purchase_currency;
}
