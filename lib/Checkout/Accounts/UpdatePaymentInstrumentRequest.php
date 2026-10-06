<?php

namespace Checkout\Accounts;

/**
 * Request for PATCH /accounts/entities/{entityId}/payment-instruments/{id}
 * (PlatformsPaymentInstrumentUpdate).
 */
class UpdatePaymentInstrumentRequest
{
    /**
     * A reference that you can use to identify the payment instrument.
     * [Optional]
     * min 1 character, max 50 characters
     *
     * @var string
     */
    public $label;

    /**
     * Deprecated by the API: for scheduled payouts the first payment instrument created for a
     * currency is used; to change it, update the payout schedule.
     * [Optional]
     *
     * @var bool
     */
    public $default;

    /**
     * The payment instrument ETag, as returned in the ETag header of the GET, in $headers->if_match.
     * AccountsClient::updateBankPaymentInstrumentDetails sends it as the If-Match HTTP header; the
     * API answers 428 without it and 412 when it does not match.
     * [Required] by the API.
     *
     * @var Headers
     */
    public $headers;
}
