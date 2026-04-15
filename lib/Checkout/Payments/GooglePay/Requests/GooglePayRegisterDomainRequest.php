<?php

namespace Checkout\Payments\GooglePay\Requests;

/**
 * Request body for POST /googlepay/enrollments/{entity_id}/domain — register a web domain for an enrolled entity.
 */
class GooglePayRegisterDomainRequest
{
    /**
     * The web domain to register for an actively enrolled entity.
     * [Required]
     * Format: hostname
     *
     * @var string
     */
    public $webDomain;
}
