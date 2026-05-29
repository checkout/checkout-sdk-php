<?php

namespace Checkout\Payments;

class InitialAuthentication
{
    /**
     * The Access Control Server (ACS) transaction ID for a previously authenticated transaction.
     * [Optional]
     * min 36 characters
     * @var string|null $acs_transaction_id
     */
    public $acs_transaction_id;

    /**
     * The authentication method used in the previous transaction.
     * [Optional]
     * Enum: "frictionless_authentication" "challenge_occurred" "avs_verified" "other_issuer_methods"
     * @var string|null $authentication_method
     */
    public $authentication_method;

    /**
     * The timestamp of the previous authentication, in ISO 8601 format.
     * [Optional]
     * Format: date-time
     * @var string|null $authentication_timestamp
     */
    public $authentication_timestamp;

    /**
     * The data that documents and supports a specific authentication process.
     * [Optional]
     * max 2048 characters
     * @var string|null $authentication_data
     */
    public $authentication_data;

    /**
     * The ID for a previous session, which is used for retrieving the initial transaction's properties.
     * [Optional]
     * min 30 characters
     * max 30 characters
     * @var string|null $initial_session_id
     */
    public $initial_session_id;
}
