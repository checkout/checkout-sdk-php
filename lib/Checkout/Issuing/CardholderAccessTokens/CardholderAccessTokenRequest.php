<?php

namespace Checkout\Issuing\CardholderAccessTokens;

class CardholderAccessTokenRequest
{
    /**
     * OAuth grant type. (Required)
     *
     * @var string
     */
    public $grant_type;

    /**
     * Access key ID. (Required)
     *
     * @var string
     */
    public $client_id;

    /**
     * Access key secret. (Required)
     *
     * @var string
     */
    public $client_secret;

    /**
     * The cardholder's unique identifier.
     *
     * @var string
     */
    public $cardholder_id;

    /**
     * Specifies if the request is for a single-use token.
     * Single-use tokens are required for sensitive endpoints.
     *
     * @var bool
     */
    public $single_use;
}
