<?php

namespace Checkout\Forward\Requests;

class Headers
{
    /**
     * The raw headers to include in the forward request (Required, max 16 characters)
     *
     * @var array<string, string>
     */
    public $raw = [];

    /**
     * The encrypted headers to include in the forward request, as a JSON object with string values encrypted
     * with JSON Web Encryption (JWE) (Optional, max 8192 characters)
     *
     * @var string|null
     */
    public $encrypted;
}
