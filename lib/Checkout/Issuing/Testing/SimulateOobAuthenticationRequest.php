<?php

namespace Checkout\Issuing\Testing;

/**
 * Request body for POST /issuing/simulate/oob/authentication — simulate an out-of-band (OOB) authentication request.
 */
class SimulateOobAuthenticationRequest
{
    /**
     * The card's unique identifier.
     * [Required]
     * Pattern: ^crd_[a-z0-9]{26}$
     * Length: 30 characters
     *
     * @var string
     */
    public $card_id;

    /**
     * Details for the simulated transaction.
     * [Optional]
     *
     * @var OobSimulateTransactionDetails|null
     */
    public $transaction_details;
}
