<?php

namespace Checkout\AgenticCommerce\Entities;

/**
 * HTTP headers required for request integrity on POST /agentic_commerce/delegate_payment (see API reference).
 */
class DelegatedPaymentHeaders
{
    /**
     * A Base64-encoded HMAC-SHA256 signature used for request body integrity verification.
     * Concatenate the Timestamp header value (UTF-8) with the raw JSON request body (UTF-8), compute HMAC-SHA256 with your shared signing key, then Base64-encode the hash.
     * [Required]
     *
     * @var string
     */
    public $signature;

    /**
     * The timestamp of the request, in RFC 3339 format (for example, 2026-03-11T10:30:00Z).
     * Must be within 5 minutes of server time or the request may be rejected with 401.
     * [Required]
     * Format: date-time (RFC 3339)
     *
     * @var string
     */
    public $timestamp;

    /**
     * The API version to use for the request. If omitted, the default API version applies.
     * [Optional]
     * Maps to HTTP header API-Version.
     *
     * @var string|null
     */
    public $api_version;

    /**
     * Get custom header name mappings for properties that don't follow standard conversion rules.
     *
     * @return array Property name to HTTP header name mappings
     */
    public function getHeaderMappings(): array
    {
        return [
            'api_version' => 'API-Version'
        ];
    }
}
