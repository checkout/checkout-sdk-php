<?php

namespace Checkout\Payments\GooglePay\Requests;

/**
 * Request body for POST /googlepay/enrollments — enroll an entity to the Google Pay service.
 * Property names match the API JSON (OpenAPI GooglePayEnrollmentRequest).
 */
class GooglePayEnrollmentRequest
{
    /**
     * The unique identifier of the entity to enroll.
     * [Required]
     *
     * @var string
     */
    public $entityId;

    /**
     * The email address of the user accepting the Google terms of service for this feature.
     * [Required]
     *
     * @var string
     */
    public $emailAddress;

    /**
     * Indicates acceptance of the Google terms of service. Must be true to proceed with enrollment.
     * [Required]
     *
     * @var bool
     */
    public $acceptTermsOfService;
}
